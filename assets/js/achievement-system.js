// Enhanced Achievement System JavaScript

// Initialize achievement animations when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeAchievements();
    animateProgressBars();
    setupAchievementInteractions();
    startFloatingIcons();
});

// Initialize achievement system
function initializeAchievements() {
    // Animate circular progress
    animateCircularProgress();
    
    // Animate XP bar
    animateXPBar();
    
    // Start badge animations
    animateBadges();
    
    // Initialize achievement notifications
    setupAchievementNotifications();
}

// Animate circular progress in hero section
function animateCircularProgress() {
    const circleProgress = document.querySelector('.circle-progress');
    if (!circleProgress) return;
    
    const percentage = 75; // Could be dynamic from data
    const degrees = (percentage / 100) * 360;
    
    // Animate the conic gradient
    let currentDegrees = 0;
    const animationDuration = 2000;
    const startTime = Date.now();
    
    function updateProgress() {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / animationDuration, 1);
        
        currentDegrees = progress * degrees;
        circleProgress.style.background = `conic-gradient(#ffd700 0deg ${currentDegrees}deg, rgba(255, 255, 255, 0.2) ${currentDegrees}deg 360deg)`;
        
        if (progress < 1) {
            requestAnimationFrame(updateProgress);
        }
    }
    
    requestAnimationFrame(updateProgress);
}

// Animate XP bar
function animateXPBar() {
    const xpProgress = document.querySelector('.xp-progress');
    if (!xpProgress) return;
    
    // Start from 0 and animate to 65%
    xpProgress.style.width = '0%';
    
    setTimeout(() => {
        xpProgress.style.width = '65%';
    }, 500);
}

// Animate progress bars with stagger effect
function animateProgressBars() {
    const progressBars = document.querySelectorAll('.animated-progress-bar .progress-fill');
    
    progressBars.forEach((bar, index) => {
        const targetWidth = bar.style.width;
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.width = targetWidth;
        }, 1000 + (index * 200)); // Stagger the animations
    });
}

// Animate badge appearances
function animateBadges() {
    const badgeCards = document.querySelectorAll('.badge-card');
    
    badgeCards.forEach((badge, index) => {
        badge.style.opacity = '0';
        badge.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            badge.style.transition = 'all 0.6s ease';
            badge.style.opacity = '1';
            badge.style.transform = 'translateY(0)';
        }, 200 + (index * 100)); // Stagger the badge animations
    });
}

// Setup achievement interactions
function setupAchievementInteractions() {
    // Add click handlers for badges
    const badgeCards = document.querySelectorAll('.badge-card');
    
    badgeCards.forEach(badge => {
        badge.addEventListener('click', function() {
            if (this.classList.contains('earned')) {
                showAchievementDetails(this);
            } else if (this.classList.contains('in-progress')) {
                showProgressDetails(this);
            } else if (this.classList.contains('locked')) {
                showUnlockRequirements(this);
            }
        });
        
        // Add hover sound effect (visual feedback)
        badge.addEventListener('mouseenter', function() {
            if (this.classList.contains('earned')) {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            }
        });
        
        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Progress card interactions
    const progressCards = document.querySelectorAll('.progress-card');
    progressCards.forEach(card => {
        card.addEventListener('click', function() {
            showProgressBreakdown(this);
        });
    });
}

// Show achievement details modal
function showAchievementDetails(badge) {
    const title = badge.querySelector('h4').textContent;
    const description = badge.querySelector('p').textContent;
    const earnedDate = badge.querySelector('.earned-date').textContent;
    const points = badge.querySelector('.badge-points').textContent;
    
    // Create achievement detail popup
    const popup = createAchievementPopup({
        title: title,
        description: description,
        earnedDate: earnedDate,
        points: points,
        type: 'earned'
    });
    
    document.body.appendChild(popup);
    
    // Animate popup appearance
    setTimeout(() => {
        popup.classList.add('show');
    }, 10);
}

// Show progress details for in-progress achievements
function showProgressDetails(badge) {
    const title = badge.querySelector('h4').textContent;
    const description = badge.querySelector('p').textContent;
    const progressText = badge.querySelector('.progress-text').textContent;
    const points = badge.querySelector('.badge-points').textContent;
    
    const popup = createAchievementPopup({
        title: title,
        description: description,
        progress: progressText,
        points: points,
        type: 'in-progress'
    });
    
    document.body.appendChild(popup);
    
    setTimeout(() => {
        popup.classList.add('show');
    }, 10);
}

// Show unlock requirements for locked achievements
function showUnlockRequirements(badge) {
    const title = badge.querySelector('h4').textContent;
    const description = badge.querySelector('p').textContent;
    const unlockCondition = badge.querySelector('.unlock-condition').textContent;
    const points = badge.querySelector('.badge-points').textContent;
    
    const popup = createAchievementPopup({
        title: title,
        description: description,
        unlockCondition: unlockCondition,
        points: points,
        type: 'locked'
    });
    
    document.body.appendChild(popup);
    
    setTimeout(() => {
        popup.classList.add('show');
    }, 10);
}

// Create achievement popup
function createAchievementPopup(data) {
    const popup = document.createElement('div');
    popup.className = 'achievement-popup';
    
    let contentHtml = `
        <div class="popup-content ${data.type}">
            <div class="popup-header">
                <h3>${data.title}</h3>
                <button class="close-btn" onclick="closeAchievementPopup(this)">&times;</button>
            </div>
            <div class="popup-body">
                <p>${data.description}</p>
    `;
    
    if (data.type === 'earned') {
        contentHtml += `
            <div class="earned-info">
                <span class="earned-date">${data.earnedDate}</span>
                <span class="earned-points">${data.points}</span>
            </div>
        `;
    } else if (data.type === 'in-progress') {
        contentHtml += `
            <div class="progress-info">
                <span class="progress-status">${data.progress}</span>
                <span class="reward-points">Reward: ${data.points}</span>
            </div>
        `;
    } else if (data.type === 'locked') {
        contentHtml += `
            <div class="unlock-info">
                <span class="unlock-requirement">${data.unlockCondition}</span>
                <span class="reward-points">Reward: ${data.points}</span>
            </div>
        `;
    }
    
    contentHtml += `
            </div>
        </div>
    `;
    
    popup.innerHTML = contentHtml;
    
    // Add popup styles
    popup.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;
    
    return popup;
}

// Close achievement popup
function closeAchievementPopup(button) {
    const popup = button.closest('.achievement-popup');
    popup.classList.remove('show');
    
    setTimeout(() => {
        popup.remove();
    }, 300);
}

// Show progress breakdown
function showProgressBreakdown(card) {
    const title = card.querySelector('.progress-title').textContent;
    const percentage = card.querySelector('.progress-percentage').textContent;
    const details = card.querySelector('.progress-details').textContent;
    
    // Create a detailed breakdown modal
    const modal = document.createElement('div');
    modal.className = 'progress-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>${title} Breakdown</h3>
                <button class="close-btn" onclick="this.parentElement.parentElement.parentElement.remove()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="progress-circle-large">
                    <span class="percentage-large">${percentage}</span>
                </div>
                <p>${details}</p>
                <div class="improvement-tips">
                    <h4>💡 Tips to Improve:</h4>
                    <ul>
                        ${getImprovementTips(title)}
                    </ul>
                </div>
            </div>
        </div>
    `;
    
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
    `;
    
    document.body.appendChild(modal);
}

// Get improvement tips based on progress type
function getImprovementTips(progressType) {
    const tips = {
        'Profile Completion': [
            'Add a professional profile picture',
            'Complete your projects section',
            'Add detailed skill descriptions',
            'Include your academic achievements'
        ],
        'Skill Development': [
            'Practice coding challenges daily',
            'Complete online courses',
            'Work on personal projects',
            'Get endorsements from peers'
        ],
        'Community Engagement': [
            'Share more study notes',
            'Help other students',
            'Participate in discussions',
            'Join study groups'
        ],
        'Learning Goals': [
            'Set SMART goals',
            'Track your progress daily',
            'Celebrate small wins',
            'Find an accountability partner'
        ]
    };
    
    const tipList = tips[progressType] || ['Keep up the great work!'];
    return tipList.map(tip => `<li>${tip}</li>`).join('');
}

// Setup achievement notifications
function setupAchievementNotifications() {
    // Simulate achievement unlock notifications
    setTimeout(() => {
        showAchievementNotification({
            title: '🎉 New Achievement Unlocked!',
            description: 'Knowledge Sharer - Upload 5 study notes',
            points: '+100 XP'
        });
    }, 3000);
}

// Show achievement notification
function showAchievementNotification(achievement) {
    const notification = document.createElement('div');
    notification.className = 'achievement-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <div class="notification-icon">🏆</div>
            <div class="notification-text">
                <h4>${achievement.title}</h4>
                <p>${achievement.description}</p>
                <span class="notification-points">${achievement.points}</span>
            </div>
        </div>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        z-index: 1000;
        transform: translateX(400px);
        transition: transform 0.5s ease;
        max-width: 300px;
    `;
    
    document.body.appendChild(notification);
    
    // Slide in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 5000);
}

// Start floating icons animation
function startFloatingIcons() {
    const floatingIcons = document.querySelectorAll('.float-icon');
    
    floatingIcons.forEach((icon, index) => {
        // Add random movement to floating icons
        setInterval(() => {
            const randomX = Math.random() * 20 - 10;
            const randomY = Math.random() * 20 - 10;
            icon.style.transform = `translate(${randomX}px, ${randomY}px) rotate(${Math.random() * 360}deg)`;
        }, 3000 + (index * 500));
    });
}

// Animate stats numbers
function animateStatsNumbers() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    statNumbers.forEach(stat => {
        const finalValue = parseInt(stat.textContent);
        let currentValue = 0;
        const increment = finalValue / 50;
        
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                stat.textContent = finalValue;
                clearInterval(timer);
            } else {
                stat.textContent = Math.floor(currentValue);
            }
        }, 30);
    });
}

// Initialize when achievements section becomes visible
const achievementSection = document.getElementById('achievements');
if (achievementSection) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStatsNumbers();
                observer.unobserve(entry.target);
            }
        });
    });
    
    observer.observe(achievementSection);
}

// Add additional CSS for popups and notifications
const style = document.createElement('style');
style.textContent = `
    .achievement-popup.show {
        opacity: 1;
    }
    
    .popup-content {
        background: white;
        border-radius: 20px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    
    .achievement-popup.show .popup-content {
        transform: scale(1);
    }
    
    .popup-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .popup-header h3 {
        color: #1e293b;
        margin: 0;
    }
    
    .close-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
    }
    
    .close-btn:hover {
        color: #1e293b;
    }
    
    .earned-info, .progress-info, .unlock-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding: 15px;
        border-radius: 10px;
    }
    
    .popup-content.earned .earned-info {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
    }
    
    .popup-content.in-progress .progress-info {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    }
    
    .popup-content.locked .unlock-info {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .notification-icon {
        font-size: 2rem;
    }
    
    .notification-text h4 {
        margin: 0 0 5px 0;
        font-size: 1.1rem;
    }
    
    .notification-text p {
        margin: 0 0 5px 0;
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .notification-points {
        font-size: 0.8rem;
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 8px;
        border-radius: 10px;
    }
`;

document.head.appendChild(style);