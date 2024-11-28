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
</style>