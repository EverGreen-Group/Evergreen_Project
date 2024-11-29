<style>

.stats-container {
    display: flex;
    align-items: center;
    background: linear-gradient(145deg, #ffffff, #f5f5f5);
    border-radius: 15px;
    padding: 15px;
    margin: 10px 5px;
    box-shadow: 0 4px 15px rgba(0, 128, 0, 0.1);
}

.stat-item {
    flex: 1;
    text-align: center;
}

.stat-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 8px;
}

.stat-header i {
    font-size: 1.2rem;
    color: #008000;
}

.stat-header span {
    font-size: 0.9rem;
    color: #4a4a4a;
    font-weight: 500;
}

.stat-value {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2b2b2b;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.stat-value small {
    font-size: 0.75rem;
    color: #666;
    font-weight: normal;
    margin-top: 2px;
}

.stat-divider {
    width: 1px;
    height: 40px;
    background: rgba(0, 128, 0, 0.1);
    margin: 0 15px;
}

/* Collection specific styles */
.collection-info .stat-value {
    font-size: 1rem;
}

.time-info, .date-info {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 4px 0;
}

.time-info i, .date-info i {
    font-size: 1rem;
    color: #008000;
}

.time-info span, .date-info span {
    color: #4a4a4a;
    font-weight: 500;
}

@media screen and (max-width: 480px) {
    .stats-container {
        padding: 12px 8px;
    }

    .stat-header {
        gap: 5px;
    }

    .stat-header i {
        font-size: 1rem;
    }

    .stat-header span {
        font-size: 0.8rem;
    }

    .stat-value {
        font-size: 1.1rem;
    }

    .stat-value small {
        font-size: 0.7rem;
    }

    .stat-divider {
        margin: 0 8px;
        height: 35px;
    }

    .time-info, .date-info {
        gap: 6px;
    }

    .time-info i, .date-info i {
        font-size: 0.9rem;
    }

    .time-info span, .date-info span {
        font-size: 0.9rem;
    }
}

.section-divider {
    height: 1px;
    background: linear-gradient(to right, rgba(0, 128, 0, 0.05), rgba(0, 128, 0, 0.2), rgba(0, 128, 0, 0.05));
    margin: 20px 5px;
    border: none;
}

.schedule-section {
    padding: 10px 5px;
}

.section-header {
    margin-bottom: 15px;
    text-align: center;
}

.section-header h3 {
    color: #2b2b2b;
    font-size: 1.1rem;
    font-weight: 600;
}

.schedule-card {
    background: linear-gradient(145deg, #ffffff, #f5f5f5);
    border-radius: 15px;
    padding: 20px;
    margin: 0 auto;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 4px 15px rgba(0, 128, 0, 0.1);
    position: relative;
    display: flex;
    align-items: center;
    gap: 15px;
}

.card-content {
    flex: 1;
}

.card-header {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.status-badge {
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    text-align: center;
}

.status-badge.pending {
    background: rgba(255, 166, 0, 0.1);
    color: orange;
}

.status-badge.today {
    background: rgba(0, 128, 0, 0.1);
    color: #008000;
}

.schedule-info {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.info-item i {
    font-size: 1.4rem;
    color: #008000;
    width: 28px;
    text-align: center;
}

.info-item span {
    color: #4a4a4a;
    font-size: 1.1rem;
}

.nav-btn {
    background: rgba(255, 255, 255, 0.8);
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
}

.nav-btn i {
    font-size: 1.8rem;
    color: #008000;
}

.nav-btn:hover {
    background: #ffffff;
    transform: scale(1.1);
}

.card-navigation {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    margin: 15px 0;
    color: #666;
    font-size: 0.9rem;
}

.action-button {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.request-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #008000;
    color: white;
    padding: 12px 25px;
    border-radius: 25px;
    border: none;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.request-btn:hover {
    background: #006700;
    transform: translateY(-2px);
}

@media screen and (max-width: 480px) {
    .schedule-card {
        padding: 15px;
        width: 95%;
    }

    .info-item i {
        font-size: 1.2rem;
        width: 24px;
    }

    .info-item span {
        font-size: 1rem;
    }

    .nav-btn {
        width: 35px;
        height: 35px;
    }

    .nav-btn i {
        font-size: 1.5rem;
    }

    .status-badge {
        padding: 5px 12px;
        font-size: 0.85rem;
    }
}

/* Current Schedule Card Styles */
.current-schedule {
    background: linear-gradient(145deg, #ffffff, #f5f5f5);
    padding: 20px;
    margin-top: 15px;
}

.current-schedule .schedule-info {
    margin-bottom: 20px;
}

.schedule-action {
    display: flex;
    justify-content: center;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid rgba(0, 128, 0, 0.1);
    width: 100%;
}

.change-schedule-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    color: #008000;
    padding: 10px 20px;
    border: 1px solid #008000;
    border-radius: 25px;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.change-schedule-btn i {
    font-size: 1.2rem;
}

.change-schedule-btn:hover {
    background: rgba(0, 128, 0, 0.1);
    transform: translateY(-2px);
}

@media screen and (max-width: 480px) {
    .change-schedule-btn {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
    
    .change-schedule-btn i {
        font-size: 1.1rem;
    }
}

.view-details-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: transparent;
    color: #008000;
    padding: 8px 20px;
    border: 1px solid #008000;
    border-radius: 25px;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    text-decoration: none;
}

.view-details-btn i {
    font-size: 1.2rem;
}

.view-details-btn:hover {
    background: rgba(0, 128, 0, 0.1);
    transform: translateY(-2px);
    color: #008000;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 15px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    animation: modalSlideIn 0.3s ease;
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid rgba(0, 128, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    color: #2b2b2b;
    margin: 0;
}

.close-modal {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #666;
    cursor: pointer;
    padding: 5px;
}

.modal-body {
    padding: 20px;
}

.detail-group {
    margin-bottom: 25px;
}

.detail-group h4 {
    color: #008000;
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.detail-item {
    display: flex;
    margin-bottom: 12px;
    padding: 8px;
    background: rgba(0, 128, 0, 0.03);
    border-radius: 8px;
}

.detail-item .label {
    width: 120px;
    color: #666;
    font-weight: 500;
}

.detail-item .value {
    color: #2b2b2b;
    font-weight: 500;
}

/* Timeline Styles */
.status-timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 25px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-dot {
    position: absolute;
    left: -30px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #ddd;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #ddd;
}

.timeline-item.active .timeline-dot {
    background: #008000;
    box-shadow: 0 0 0 2px rgba(0, 128, 0, 0.2);
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -24px;
    top: 12px;
    height: 100%;
    width: 2px;
    background: #ddd;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-content {
    padding-left: 15px;
}

.timeline-content .time {
    color: #666;
    font-size: 0.9rem;
    margin: 0;
}

.timeline-content .status {
    color: #2b2b2b;
    font-weight: 500;
    margin: 5px 0 0;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@media screen and (max-width: 480px) {
    .modal-content {
        width: 95%;
    }

    .detail-item {
        flex-direction: column;
    }

    .detail-item .label {
        width: 100%;
        margin-bottom: 4px;
    }
}
</style>