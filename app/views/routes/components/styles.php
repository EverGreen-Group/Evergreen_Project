<style>
    .create-route-container {
        display: flex;
        gap: 20px;
        width: 100%;
    }

    .map-section {
        flex: 1.5;
        background: var(--light);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .options-section {
        flex: 1;
        background: var(--light);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--dark);
    }

    .form-group input[type="text"] {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .form-group input[type="text"]:focus {
        border-color: var(--blue);
        outline: none;
        box-shadow: 0 0 0 2px rgba(53, 127, 190, 0.1);
    }

    .form-group select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        background-color: white;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        padding-right: 35px;
    }

    .form-group select:focus {
        border-color: var(--blue);
        outline: none;
        box-shadow: 0 0 0 2px rgba(53, 127, 190, 0.1);
    }

    .form-group select:disabled {
        background-color: #f5f5f5;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .form-group input::placeholder {
        color: #999;
    }

    .form-group select option {
        padding: 10px;
    }

    .form-group input[type="text"]:hover,
    .form-group select:hover:not(:disabled) {
        border-color: #b3b3b3;
    }

    .form-group.error input[type="text"],
    .form-group.error select {
        border-color: var(--red);
    }

    .form-group.error label {
        color: var(--red);
    }

    .form-group.success input[type="text"],
    .form-group.success select {
        border-color: var(--green);
    }

    .vehicle-details {
        margin-top: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .vehicle-details h4 {
        margin-bottom: 10px;
        color: var(--dark);
    }

    .vehicle-details p {
        margin: 10px 0;
        font-size: 14px;
        color: var(--dark-grey);
    }

    .vehicle-details span {
        font-weight: 500;
        color: var(--dark);
    }

    @media screen and (max-width: 768px) {
        .create-route-container {
            flex-direction: column;
        }
    }

    .suppliers-section {
        margin-top: 20px;
    }

    .suppliers-section h4 {
        margin-bottom: 10px;
        color: var(--dark);
    }

    .suppliers-list {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
    }

    .supplier-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #eee;
        transition: background-color 0.3s ease;
    }

    .supplier-item:last-child {
        border-bottom: none;
    }

    .supplier-item:hover {
        background-color: #f8f9fa;
    }

    .supplier-info {
        flex: 1;
    }

    .supplier-name {
        display: block;
        font-weight: 500;
        color: var(--dark);
        margin-bottom: 4px;
    }

    .supplier-details {
        font-size: 0.85rem;
        color: var(--dark-grey);
    }

    .supplier-details span {
        margin-right: 15px;
    }

    .add-supplier-btn {
        background: var(--blue);
        color: white;
        border: none;
        border-radius: 5px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .add-supplier-btn:hover {
        background-color: #2980b9;
    }

    /* Scrollbar styling */
    .suppliers-list::-webkit-scrollbar {
        width: 8px;
    }

    .suppliers-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .suppliers-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .suppliers-list::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .selected-suppliers-section {
        margin: 20px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
    }

    .selected-suppliers-list {
        max-height: 200px;
        overflow-y: auto;
    }

    .selected-supplier-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .order-number {
        display: inline-block;
        width: 24px;
        height: 24px;
        line-height: 24px;
        text-align: center;
        background: var(--blue);
        color: white;
        border-radius: 50%;
        margin-right: 10px;
    }

    .supplier-item.selected {
        background-color: #e8f5e9;
    }

    .empty-message {
        text-align: center;
        color: var(--dark-grey);
        padding: 20px;
    }

    .remove-supplier-btn {
        background: var(--red);
        color: white;
        border: none;
        border-radius: 5px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>

<style>
       /* Add these new styles */
       .shifts-overview {
        display: flex;
        gap: 20px;
        padding: 15px;
    }

    .shift-block {
        flex: 1;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
    }

    .shift-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .shift-header i {
        font-size: 1.2rem;
        color: var(--main);
    }

    .shift-header h4 {
        font-size: 1rem;
        color: #333;
        margin: 0;
    }

    /* Capacity Overview Route Cards */
    .shifts-overview .routes-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .shifts-overview .route-card {
        background: white;
        border-radius: 6px;
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        width: auto; /* Override the width from other route cards */
        transform: none; /* Remove hover transform */
        cursor: default; /* Remove pointer cursor */
    }

    .shifts-overview .route-info {
        margin-bottom: 8px;
    }

    .shifts-overview .route-name {
        font-weight: 600;
        color: #2c3e50;
        display: block;
        margin-bottom: 4px;
    }

    .shifts-overview .route-path {
        font-size: 0.85rem;
        color: #666;
        display: block;
    }

    .capacity-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .capacity-bar {
        flex: 1;
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .capacity-text {
        font-size: 0.85rem;
        color: #666;
        min-width: 90px;
        text-align: right;
    }

    /* Capacity fill colors based on percentage */
    .capacity-fill {
        height: 100%;
        background-color: #4CAF50;
        transition: width 0.3s ease;
    }

    .capacity-fill[style*="width: 7"], 
    .capacity-fill[style*="width: 8"] {
        background-color: #ff9800;
    }

    .capacity-fill[style*="width: 9"] {
        background-color: #f44336;
    }

    /* Routes Section Cards (different from capacity overview cards) */
    .routes-section .route-card {
        background: var(--light);
        border-radius: 10px;
        padding: 15px;
        width: calc(25% - 15px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .routes-section .route-card:hover {
        transform: translateY(-5px);
    }

    /* Responsive Design */
    @media screen and (max-width: 1200px) {
        .shifts-overview {
            flex-direction: column;
        }

        .shift-block {
            width: 100%;
        }
    }

    @media screen and (max-width: 1024px) {
        .routes-section .route-card {
            width: calc(33.33% - 13.33px);
        }
    }

    @media screen and (max-width: 768px) {
        .routes-section .route-card {
            width: calc(50% - 10px);
        }
    }

    @media screen and (max-width: 480px) {
        .routes-section .route-card {
            width: 100%;
        }
    }

    #map {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        background-color: #f5f5f5; /* Light grey background before map loads */
    }

    .route-map-section {
        flex: 1;
        min-height: 400px;
        background: var(--light);
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .route-actions {
        display: flex;
        gap: 10px;
    }

    .btn-create, .btn-update {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-create {
        background-color: var(--blue);
        color: white;
    }

    .btn-update {
        background-color: var(--yellow);
        color: var(--dark);
    }

    .btn-create:hover {
        background-color: #2980b9;
    }

    .btn-update:hover {
        background-color: #f39c12;
    }

    .btn-create:disabled, .btn-update:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .btn-create i, .btn-update i {
        font-size: 16px;
    }
</style>