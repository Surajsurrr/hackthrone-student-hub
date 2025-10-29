<?php
require_once 'includes/college_auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .events-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .events-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .events-header h2 {
            margin: 0;
            color: #1e293b;
        }

        .events-grid {
            display: grid;
            gap: 1.5rem;
        }

        .event-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .event-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .event-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0 0 0.5rem 0;
        }

        .event-type-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .event-type-badge.hackathon {
            background: #dbeafe;
            color: #1e40af;
        }

        .event-type-badge.symposium {
            background: #fce7f3;
            color: #be185d;
        }

        .event-type-badge.project-expo {
            background: #dcfce7;
            color: #15803d;
        }

        .event-type-badge.workshop {
            background: #fef3c7;
            color: #92400e;
        }

        .event-description {
            color: #64748b;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .event-details {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
            color: #64748b;
            font-size: 0.9rem;
        }

        .event-detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .event-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-delete {
            background: #dc2626;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            background: #b91c1c;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #64748b;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="events-container">
        <div class="events-header">
            <h2>📅 Manage Events</h2>
            <a href="post_event.php" class="btn btn-primary">+ Post New Event</a>
        </div>

        <div id="eventsContent">
            <p style="text-align: center; color: #64748b;">Loading events...</p>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
    <script>
        let allEvents = [];

        async function loadEvents() {
            try {
                const response = await fetch('../api/college/getEvents.php');
                const data = await response.json();
                
                if (data.events) {
                    allEvents = data.events;
                    displayEvents(allEvents);
                } else if (data.error) {
                    showError(data.error);
                }
            } catch (error) {
                console.error('Error loading events:', error);
                showError('Failed to load events. Please try again later.');
            }
        }

        function displayEvents(events) {
            const container = document.getElementById('eventsContent');
            
            if (!events || events.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">📅</div>
                        <h3>No Events Posted Yet</h3>
                        <p>Start by posting your first event for students!</p>
                        <a href="post_event.php" class="btn btn-primary" style="margin-top: 1rem;">Post Event</a>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <div class="events-grid">
                    ${events.map(event => {
                        const eventDate = new Date(event.date);
                        const formattedDate = eventDate.toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });
                        const formattedTime = eventDate.toLocaleTimeString('en-US', { 
                            hour: '2-digit', 
                            minute: '2-digit' 
                        });
                        
                        return `
                            <div class="event-card">
                                <div class="event-header">
                                    <div>
                                        <h3 class="event-title">${escapeHtml(event.title)}</h3>
                                        <span class="event-type-badge ${event.type}">${event.type.replace('-', ' ')}</span>
                                    </div>
                                </div>
                                
                                <p class="event-description">${escapeHtml(event.description || 'No description')}</p>
                                
                                <div class="event-details">
                                    <div class="event-detail-item">
                                        <span>📅</span>
                                        <span>${formattedDate} at ${formattedTime}</span>
                                    </div>
                                    ${event.location ? `
                                        <div class="event-detail-item">
                                            <span>📍</span>
                                            <span>${escapeHtml(event.location)}</span>
                                        </div>
                                    ` : ''}
                                    ${event.max_participants ? `
                                        <div class="event-detail-item">
                                            <span>👥</span>
                                            <span>Max ${event.max_participants} participants</span>
                                        </div>
                                    ` : ''}
                                    <div class="event-detail-item">
                                        <span>📊</span>
                                        <span>Status: <strong>${event.status}</strong></span>
                                    </div>
                                </div>
                                
                                <div class="event-actions">
                                    <button class="btn-delete" onclick="deleteEvent(${event.id})">
                                        🗑️ Delete Event
                                    </button>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            `;
        }

        async function deleteEvent(eventId) {
            if (!confirm('Are you sure you want to delete this event?')) {
                return;
            }

            try {
                const response = await fetch('../api/college/deleteEvent.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ event_id: eventId })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('✓ Event deleted successfully');
                    loadEvents(); // Reload the events list
                } else {
                    alert('❌ Error: ' + (result.error || 'Failed to delete event'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ An error occurred while deleting the event');
            }
        }

        function showError(message) {
            document.getElementById('eventsContent').innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">❌</div>
                    <h3>Error Loading Events</h3>
                    <p>${message}</p>
                    <button onclick="loadEvents()" class="btn btn-primary" style="margin-top: 1rem;">Try Again</button>
                </div>
            `;
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Load events on page load
        document.addEventListener('DOMContentLoaded', loadEvents);
    </script>
</body>
</html>
