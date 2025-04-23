const Chat = {
  URLROOT: "",
  currentUserId: null,
  currentChatUserId: null,
  lastMessageId: 0,
  role: "",
  endpoint: "",
  pollingInterval: null,
  sentMessages: new Set(),
  isSendingMessage: false,

  init(urlRoot, userId, csrfToken) {
    this.URLROOT = urlRoot;
    this.currentUserId = userId;
    this.bindEvents();
  },

  bindEvents() {
    document
      .getElementById("send-message-btn")
      ?.addEventListener("click", () => this.sendMessage());
    document
      .getElementById("message-input")
      ?.addEventListener("keypress", (e) => {
        if (e.key === "Enter" && !e.shiftKey) {
          e.preventDefault();
          this.sendMessage();
        }
      });
  },

  async fetchWithRetry(url, options, retries = 3, backoff = 1000) {
    for (let i = 0; i < retries; i++) {
      try {
        const response = await fetch(url, options);
        if (!response.ok)
          throw new Error(`HTTP error! Status: ${response.status}`);
        return await response.json();
      } catch (error) {
        if (i === retries - 1) throw error;
        await new Promise((resolve) =>
          setTimeout(resolve, backoff * Math.pow(2, i))
        );
      }
    }
  },

  async sendMessage() {
    const messageInput = document.getElementById("message-input");
    const message = messageInput.value.trim();

    if (!message || !this.currentChatUserId) {
      this.showError("Cannot send empty messages");
      return;
    }

    const messageKey = `${this.currentUserId}:${
      this.currentChatUserId
    }:${message}:${Date.now()}`;
    if (this.sentMessages.has(messageKey)) {
      this.showError(
        "Duplicate message detected. Please wait before sending the same message again."
      );
      return;
    }
    this.sentMessages.add(messageKey);
    setTimeout(() => this.sentMessages.delete(messageKey), 5000);

    this.isSendingMessage = true;
    try {
      const data = await this.fetchWithRetry(
        `${this.URLROOT}/${this.endpoint}/sendMessage`,
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            receiver_id: this.currentChatUserId,
            message,
          }),
        }
      );

      if (data.success) {
        const existingMessage = document.querySelector(
          `[data-msg-id="${data.data.message_id}"]`
        );
        if (!existingMessage) {
          this.appendMessage({
            message_id: data.data.message_id,
            sender_id: this.currentUserId,
            receiver_id: this.currentChatUserId,
            message,
            created_at: data.data.created_at,
            read_at: null,
            edited_at: null,
            message_type: "text",
            sender_name: "Me",
          });
          this.lastMessageId = Math.max(
            this.lastMessageId,
            data.data.message_id
          );
          console.log(
            `[sendMessage] Added message ID: ${data.data.message_id}, lastMessageId: ${this.lastMessageId}`
          );
        }
        messageInput.value = "";
      } else {
        this.showError(data.message || "Error sending message");
      }
    } catch (error) {
      console.error("[sendMessage] Error:", error);
      this.showError(`Failed to send message: ${error.message}`);
      this.sentMessages.delete(messageKey);
    } finally {
      this.isSendingMessage = false;
    }
  },

  async fetchMessages(receiverId, endpoint) {
    const messagesContainer = document.getElementById("chat-messages");
    messagesContainer.innerHTML = '<div class="spinner">Loading...</div>';

    try {
      const data = await this.fetchWithRetry(
        `${this.URLROOT}/${endpoint}/getMessages`,
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ receiver_id: receiverId, last_message_id: 0 }),
        }
      );

      console.log("[fetchMessages] Response:", data);

      if (data.success && data.data && Array.isArray(data.data.messages)) {
        this.displayMessages(data.data.messages, true, this.role);
        this.startPolling(endpoint);
      } else {
        messagesContainer.innerHTML = "No messages found.";
        const errorMessage = data.message || "Invalid response structure";
        this.showError(errorMessage);
        console.error("[fetchMessages] Invalid response:", data);
      }
    } catch (error) {
      console.error("[fetchMessages] Error:", error);
      messagesContainer.innerHTML = "Error loading messages.";
      this.showError(`Failed to load messages: ${error.message}`);
    }
  },

  async fetchNewMessages(receiverId, endpoint) {
    try {
      const data = await this.fetchWithRetry(
        `${this.URLROOT}/${endpoint}/getMessages`,
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            receiver_id: receiverId,
            last_message_id: this.lastMessageId,
          }),
        }
      );

      console.log("[fetchNewMessages] Response:", data);

      if (data.success && data.data && Array.isArray(data.data.messages)) {
        // Only display new messages if there are any
        if (data.data.messages.length > 0) {
          this.displayMessages(data.data.messages, false, this.role);
        }
        // Don't do anything if messages array is empty - this is the key fix
      } else {
        const errorMessage =
          data.message || "Invalid response structure in fetchNewMessages";
        console.error("[fetchNewMessages] Invalid response:", data);
        // Only log the error, don't display to user unless critical
      }
    } catch (error) {
      console.error("[fetchNewMessages] Error:", error);
      // Only log the error, don't display to user unless critical
    }
  },

  startPolling(endpoint) {
    if (this.pollingInterval) clearInterval(this.pollingInterval);
    this.endpoint = endpoint;
    this.pollingInterval = setInterval(() => {
      if (this.currentChatUserId && !this.isSendingMessage) {
        this.fetchNewMessages(this.currentChatUserId, endpoint);
      }
    }, 10000);
  },

  stopPolling() {
    if (this.pollingInterval) {
      clearInterval(this.pollingInterval);
      this.pollingInterval = null;
    }
  },

  displayMessages(messages, clearPrevious, role) {
    const messagesContainer = document.getElementById("chat-messages");

    // Only clear if explicitly told to AND there are messages to display
    if (clearPrevious) {
      if (messages && messages.length > 0) {
        messagesContainer.innerHTML = "";
      } else {
        // Don't clear if there are no new messages
        if (messagesContainer.innerHTML === "") {
          messagesContainer.innerHTML = "No messages found.";
        }
        console.log(
          "[displayMessages] Empty messages array, keeping existing content"
        );
        return;
      }
    }

    if (!messages || !Array.isArray(messages)) {
      // Only update message container if it's empty
      if (messagesContainer.innerHTML === "") {
        messagesContainer.innerHTML = "No messages available.";
      }
      console.log("[displayMessages] No messages or invalid array");
      return;
    }

    if (messages.length === 0 && clearPrevious) {
      // Only update message container if it's empty
      if (messagesContainer.innerHTML === "") {
        messagesContainer.innerHTML = "No messages found.";
      }
      console.log("[displayMessages] Empty messages array");
      return;
    }

    const currentMessageIds = new Set(
      Array.from(document.querySelectorAll("[data-msg-id]")).map((div) =>
        parseInt(div.dataset.msgId)
      )
    );
    const newMessageIds = new Set(messages.map((msg) => msg.message_id));

    console.log("[displayMessages] Current UI message IDs:", [
      ...currentMessageIds,
    ]);
    console.log("[displayMessages] Server message IDs:", [...newMessageIds]);

    // Only remove messages that are confirmed deleted from server
    if (messages.length > 0) {
      currentMessageIds.forEach((msgId) => {
        if (!newMessageIds.has(msgId)) {
          console.log(
            `[displayMessages] Removing deleted message ID: ${msgId}`
          );
          this.deleteMessage(msgId);
        }
      });
    }

    messages.forEach((message) => {
      const existingMessage = document.querySelector(
        `[data-msg-id="${message.message_id}"]`
      );
      if (existingMessage) {
        const currentText = existingMessage
          .querySelector("b")
          .nextSibling.textContent.trim();
        const currentEditedAt = existingMessage.dataset.editedTime || "NULL";
        const serverEditedAt = message.edited_at || "NULL";
        if (
          currentText !== message.message ||
          currentEditedAt !== serverEditedAt
        ) {
          console.log(
            `[displayMessages] Updating message ID: ${message.message_id}, new text: ${message.message}, edited_at: ${serverEditedAt}`
          );
          this.updateMessage(message);
        }
      } else {
        console.log(
          `[displayMessages] Appending message ID: ${message.message_id}, text: ${message.message}`
        );
        this.appendMessage(message);
        this.lastMessageId = Math.max(this.lastMessageId, message.message_id);
      }
    });

    console.log(
      `[displayMessages] Updated lastMessageId to: ${this.lastMessageId}`
    );
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
  },

  appendMessage(data) {
    const messagesDiv = document.getElementById("chat-messages");
    const messageDiv = document.createElement("div");
    const isSent = data.sender_id == this.currentUserId;
    const rowClass = isSent
      ? "row justify-content-start"
      : "row justify-content-end";
    const backgroundClass = isSent ? "alert-primary" : "alert-success";
    const from = isSent ? "Me" : data.sender_name || this.role;

    messageDiv.className = `message ${isSent ? "sent" : "received"}`;
    messageDiv.dataset.msgId = data.message_id;
    messageDiv.dataset.sentTime = data.created_at || "N/A";
    messageDiv.dataset.readTime = data.read_at || "NULL";
    messageDiv.dataset.editedTime = data.edited_at || "NULL";
    messageDiv.dataset.messageType = data.message_type || "text";
    messageDiv.innerHTML = `
            <div class="${rowClass}">
                <div class="col-sm-10">
                    <div class="shadow-sm alert ${backgroundClass}">
                        <b>${from}: </b>${data.message}<br />
                        ${
                          isSent
                            ? `
                            <button class="view-details-btn btn btn-icon" title="View Details">
                                <i class='bx bx-star'></i>
                            </button>
                            <button class="edit-msg-btn btn btn-icon" title="Edit">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="delete-msg-btn btn btn-icon" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        `
                            : ""
                        }
                    </div>
                </div>
            </div>
        `;
    messagesDiv.appendChild(messageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
    console.log(`[appendMessage] Added message ID: ${data.message_id}`);
  },

  updateMessage(data) {
    const messagesDiv = document.getElementById("chat-messages");
    const messageDiv = document.querySelector(
      `[data-msg-id="${data.message_id}"]`
    );
    if (messageDiv) {
      const isSent = data.sender_id == this.currentUserId;
      const rowClass = isSent
        ? "row justify-content-start"
        : "row justify-content-end";
      const backgroundClass = isSent ? "alert-primary" : "alert-success";
      const from = isSent ? "Me" : data.sender_name || this.role;

      messageDiv.dataset.sentTime = data.created_at || "N/A";
      messageDiv.dataset.readTime = data.read_at || "NULL";
      messageDiv.dataset.editedTime = data.edited_at || "NULL";
      messageDiv.dataset.messageType = data.message_type || "text";
      messageDiv.innerHTML = `
                <div class="${rowClass}">
                    <div class="col-sm-10">
                        <div class="shadow-sm alert ${backgroundClass}">
                            <b>${from}: </b>${data.message}<br />
                            ${
                              isSent
                                ? `
                                <button class="view-details-btn btn btn-icon" title="View Details">
                                    <i class='bx bx-star'></i>
                                </button>
                                <button class="edit-msg-btn btn btn-icon" title="Edit">
                                    <i class='bx bx-edit'></i>
                                </button>
                                <button class="delete-msg-btn btn btn-icon" title="Delete">
                                    <i class='bx bx-trash'></i>
                                </button>
                            `
                                : ""
                            }
                        </div>
                    </div>
                </div>
            `;
      messagesDiv.scrollTop = messagesDiv.scrollHeight;
      console.log(
        `[updateMessage] Updated message ID: ${data.message_id}, new text: ${data.message}`
      );
    }
  },

  deleteMessage(messageId) {
    const messageDiv = document.querySelector(`[data-msg-id="${messageId}"]`);
    if (messageDiv) {
      messageDiv.remove();
      console.log(`[deleteMessage] Removed message ID: ${messageId}`);
    }
  },

  bindUserSelection(role, endpoint) {
    this.role = role;
    this.endpoint = endpoint;
    document.querySelectorAll(".user-item").forEach((item) => {
      item.addEventListener("click", () => {
        document
          .querySelectorAll(".user-item")
          .forEach((i) => i.classList.remove("active"));
        item.classList.add("active");

        document.querySelector(".no-chat-selected").style.display = "none";
        document.querySelector(".chat-interface").style.display = "flex";

        this.currentChatUserId = item.dataset.userId;
        this.lastMessageId = 0;
        document.getElementById("current-chat-name").textContent =
          item.querySelector("h4").textContent;

        this.fetchMessages(this.currentChatUserId, endpoint);
      });
    });
  },

  bindSearch(searchInputId) {
    document
      .getElementById(searchInputId)
      ?.addEventListener("input", function (e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll(".user-item").forEach((item) => {
          const name =
            item.querySelector("h4")?.textContent.toLowerCase() || "";
          const id = item.querySelector("p")?.textContent.toLowerCase() || "";
          item.style.display =
            name.includes(searchTerm) || id.includes(searchTerm)
              ? "flex"
              : "none";
        });
      });
  },

  bindMessageActions(endpoint) {
    document.addEventListener("click", async (e) => {
      if (e.target.closest(".edit-msg-btn")) {
        const messageDiv = e.target.closest(".message");
        const messageId = messageDiv.dataset.msgId;
        const currentMessage = messageDiv
          .querySelector("b")
          .nextSibling.textContent.trim();

        document.getElementById("edit-message-text").value = currentMessage;
        document.querySelector(".edit-message-modal").style.display = "block";

        document.getElementById("save-edit-btn").onclick = async () => {
          const newMessage = document
            .getElementById("edit-message-text")
            .value.trim();
          if (!newMessage) {
            this.showError("Message cannot be empty.");
            return;
          }

          try {
            const data = await this.fetchWithRetry(
              `${this.URLROOT}/${endpoint}/editMessage`,
              {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                  message_id: messageId,
                  new_message: newMessage,
                }),
              }
            );

            if (data.success) {
              this.updateMessage({
                message_id: messageId,
                sender_id: this.currentUserId,
                receiver_id: this.currentChatUserId,
                message: newMessage,
                created_at: messageDiv.dataset.sentTime,
                read_at: messageDiv.dataset.readTime,
                edited_at: new Date().toISOString(),
                message_type: messageDiv.dataset.messageType,
                sender_name: "Me",
              });
              document.querySelector(".edit-message-modal").style.display =
                "none";
              console.log(
                `[editMessage] Edited message ID: ${messageId}, new text: ${newMessage}`
              );
            } else {
              this.showError(data.message || "Error editing message");
            }
          } catch (error) {
            console.error("[editMessage] Error:", error);
            this.showError(`Failed to edit message: ${error.message}`);
          }
        };

        document.getElementById("cancel-edit-btn").onclick = () => {
          document.querySelector(".edit-message-modal").style.display = "none";
        };
      }

      if (e.target.closest(".delete-msg-btn")) {
        const messageDiv = e.target.closest(".message");
        const messageId = messageDiv.dataset.msgId;

        document.querySelector(".confirm-modal").style.display = "block";
        document.getElementById("confirm-message").textContent =
          "Are you sure you want to delete this message?";

        document.getElementById("confirm-ok-btn").onclick = async () => {
          try {
            const data = await this.fetchWithRetry(
              `${this.URLROOT}/${endpoint}/deleteMessage`,
              {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ message_id: messageId }),
              }
            );

            if (data.success) {
              this.deleteMessage(messageId);
              document.querySelector(".confirm-modal").style.display = "none";
              console.log(
                `[deleteMessage] Confirmed deletion of message ID: ${messageId}`
              );
            } else {
              this.showError(data.message || "Error deleting message");
            }
          } catch (error) {
            console.error("[deleteMessage] Error:", error);
            this.showError(`Failed to delete message: ${error.message}`);
          }
        };

        document.getElementById("confirm-cancel-btn").onclick = () => {
          document.querySelector(".confirm-modal").style.display = "none";
          console.log("[deleteMessage] Cancelled deletion");
        };
      }

      if (e.target.closest(".view-details-btn")) {
        const messageDiv = e.target.closest(".message");
        const sentTime = messageDiv.dataset.sentTime || "N/A";
        const readTime = messageDiv.dataset.readTime || "Not read";
        const editedTime = messageDiv.dataset.editedTime || "Not edited";
        const messageType = messageDiv.dataset.messageType || "text";

        document.getElementById("detail-sent-time").textContent = sentTime;
        document.getElementById("detail-read-time").textContent = readTime;
        document.getElementById("detail-edited-time").textContent = editedTime;
        document.getElementById("detail-message-type").textContent =
          messageType;

        document.querySelector(".view-details-modal").style.display = "block";
      }
    });

    document.querySelectorAll(".close-modal").forEach((button) => {
      button.addEventListener("click", () => {
        document.querySelector(".edit-message-modal").style.display = "none";
        document.querySelector(".view-details-modal").style.display = "none";
        document.querySelector(".confirm-modal").style.display = "none";
      });
    });

    document
      .getElementById("close-details-btn")
      ?.addEventListener("click", () => {
        document.querySelector(".view-details-modal").style.display = "none";
      });
  },

  showError(message) {
    // Check if a flash container exists, if not create one
    let flashContainer = document.getElementById("flash-messages");
    if (!flashContainer) {
      flashContainer = document.createElement("div");
      flashContainer.id = "flash-messages";
      flashContainer.style.position = "fixed";
      flashContainer.style.top = "20px";
      flashContainer.style.right = "20px";
      flashContainer.style.zIndex = "1000";
      flashContainer.style.display = "flex";
      flashContainer.style.flexDirection = "column";
      flashContainer.style.gap = "10px";
      document.body.appendChild(flashContainer);
    }

    // Create a flash message element (toast)
    const flashMessage = document.createElement("div");
    flashMessage.className = "toast-message";
    flashMessage.style.backgroundColor = "#f44336"; // Red background for error
    flashMessage.style.color = "#fff";
    flashMessage.style.padding = "12px 20px";
    flashMessage.style.borderRadius = "8px";
    flashMessage.style.boxShadow = "0 4px 12px rgba(0, 0, 0, 0.15)";
    flashMessage.style.fontFamily = "'Poppins', sans-serif";
    flashMessage.style.fontWeight = "500";
    flashMessage.style.fontSize = "14px";
    flashMessage.style.display = "flex";
    flashMessage.style.alignItems = "center";
    flashMessage.style.gap = "10px";
    flashMessage.style.maxWidth = "300px";
    flashMessage.style.opacity = "0";
    flashMessage.style.transform = "translateY(-20px)";
    flashMessage.style.transition = "opacity 0.3s ease, transform 0.3s ease";

    flashMessage.innerHTML = `
            <span>${message}</span>
            <button class="close-toast" style="border: none; background: none; color: #fff; cursor: pointer; font-size: 16px; line-height: 1;">×</button>
        `;

    // Add the toast to the container
    flashContainer.appendChild(flashMessage);

    // Trigger the pop-up animation
    setTimeout(() => {
      flashMessage.style.opacity = "1";
      flashMessage.style.transform = "translateY(0)";
    }, 10);

    // Automatically remove the toast after 3 seconds
    setTimeout(() => {
      flashMessage.style.opacity = "0";
      flashMessage.style.transform = "translateY(-20px)";
      setTimeout(() => {
        flashMessage.remove();
      }, 300);
    }, 3000);

    // Allow manual closing of the toast
    flashMessage.querySelector(".close-toast").addEventListener("click", () => {
      flashMessage.style.opacity = "0";
      flashMessage.style.transform = "translateY(-20px)";
      setTimeout(() => {
        flashMessage.remove();
      }, 300);
    });

    console.log("[showError]", message);
  },
};

export default Chat;
