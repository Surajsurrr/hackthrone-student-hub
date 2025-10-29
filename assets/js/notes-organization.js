// Enhanced Notes Organization JavaScript

// Global variables
let availableTopics = [];
let selectedTopics = [];
let activeFilters = {};

// Initialize enhanced organization features
document.addEventListener('DOMContentLoaded', function() {
    loadCategoriesData();
    setupTopicSelector();
    setupFilterHandlers();
    setupCategoryNavigation();
});

// Load categories, topics, and stats
async function loadCategoriesData() {
    try {
        const response = await fetch('../api/student/getCategories.php', {
            credentials: 'same-origin'
        });
        const data = await response.json();
        
        if (data.success) {
            availableTopics = data.topics;
            renderSubjectCategories(data.subjects);
            populateTopicSelectors(data.topics);
            renderTopicChips(data.topics);
            updateStats(data.stats);
        } else {
            console.error('Failed to load categories:', data.error);
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

// Render subject categories navigation
function renderSubjectCategories(subjects) {
    const grid = document.getElementById('categories-grid');
    if (!grid) return;
    
    grid.innerHTML = subjects.map(subject => `
        <div class="category-card" data-subject="${subject.name}" onclick="filterBySubject('${subject.name}')">
            <span class="category-icon">${subject.icon}</span>
            <div class="category-name">${subject.name}</div>
            <div class="category-count">${subject.notes_count} notes • ${subject.topic_count} topics</div>
        </div>
    `).join('');
}

// Populate topic selectors in upload form
function populateTopicSelectors(topics) {
    const topicSelect = document.getElementById('note-topics-select');
    if (!topicSelect) return;
    
    // Group topics by subject
    const topicsBySubject = {};
    topics.forEach(topic => {
        if (!topicsBySubject[topic.subject_category]) {
            topicsBySubject[topic.subject_category] = [];
        }
        topicsBySubject[topic.subject_category].push(topic);
    });
    
    // Clear existing options
    topicSelect.innerHTML = '<option value="">Select topics...</option>';
    
    // Add grouped options
    Object.keys(topicsBySubject).forEach(subject => {
        const optgroup = document.createElement('optgroup');
        optgroup.label = subject;
        
        topicsBySubject[subject].forEach(topic => {
            const option = document.createElement('option');
            option.value = topic.id;
            option.textContent = `${topic.icon} ${topic.name}`;
            option.dataset.color = topic.color;
            optgroup.appendChild(option);
        });
        
        topicSelect.appendChild(optgroup);
    });
    
    // Update topic selector when subject changes
    const subjectSelect = document.getElementById('note-subject');
    if (subjectSelect) {
        subjectSelect.addEventListener('change', function() {
            filterTopicsBySubject(this.value);
        });
    }
}

// Filter topics by selected subject
function filterTopicsBySubject(selectedSubject) {
    const topicSelect = document.getElementById('note-topics-select');
    if (!topicSelect) return;
    
    const optgroups = topicSelect.querySelectorAll('optgroup');
    optgroups.forEach(optgroup => {
        if (selectedSubject === '' || optgroup.label === selectedSubject) {
            optgroup.style.display = 'block';
        } else {
            optgroup.style.display = 'none';
        }
    });
}

// Render topic filter chips
function renderTopicChips(topics) {
    const chipsContainer = document.getElementById('topic-chips');
    if (!chipsContainer) return;
    
    // Show most popular topics
    const popularTopics = topics
        .filter(topic => topic.usage_count > 0)
        .sort((a, b) => b.usage_count - a.usage_count)
        .slice(0, 15);
    
    chipsContainer.innerHTML = popularTopics.map(topic => `
        <button class="topic-chip" data-topic-id="${topic.id}" onclick="toggleTopicFilter(${topic.id}, '${topic.name}')">
            ${topic.icon} ${topic.name} (${topic.usage_count})
        </button>
    `).join('');
}

// Setup topic selector for upload form
function setupTopicSelector() {
    const topicSelect = document.getElementById('note-topics-select');
    if (!topicSelect) return;
    
    topicSelect.addEventListener('change', function() {
        const selectedOptions = Array.from(this.selectedOptions);
        
        selectedOptions.forEach(option => {
            const topicId = option.value;
            const topicName = option.textContent;
            
            if (topicId && !selectedTopics.find(t => t.id === topicId)) {
                selectedTopics.push({ id: topicId, name: topicName });
                renderSelectedTopics();
            }
        });
        
        // Clear selection
        this.selectedIndex = 0;
    });
}

// Render selected topics in upload form
function renderSelectedTopics() {
    const container = document.getElementById('selected-topics');
    if (!container) return;
    
    container.innerHTML = selectedTopics.map(topic => `
        <span class="topic-tag">
            ${topic.name}
            <button type="button" class="remove-topic" onclick="removeSelectedTopic('${topic.id}')">×</button>
        </span>
    `).join('');
}

// Remove selected topic
function removeSelectedTopic(topicId) {
    selectedTopics = selectedTopics.filter(topic => topic.id !== topicId);
    renderSelectedTopics();
}

// Setup filter handlers
function setupFilterHandlers() {
    // Search input
    const searchInput = document.getElementById('search-notes');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(handleSearch, 300));
    }
    
    // Filter selects
    ['filter-subject', 'filter-topic', 'filter-difficulty', 'sort-notes'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', handleFilterChange);
        }
    });
    
    // Clear filters button
    const clearFilters = document.getElementById('clear-filters');
    if (clearFilters) {
        clearFilters.addEventListener('click', clearAllFilters);
    }
}

// Handle search
function handleSearch(event) {
    const query = event.target.value.trim();
    if (query) {
        addActiveFilter('search', query, `Search: "${query}"`);
    } else {
        removeActiveFilter('search');
    }
    applyFilters();
}

// Handle filter changes
function handleFilterChange(event) {
    const filterId = event.target.id;
    const value = event.target.value;
    const text = event.target.options[event.target.selectedIndex].text;
    
    if (value) {
        addActiveFilter(filterId, value, text);
    } else {
        removeActiveFilter(filterId);
    }
    applyFilters();
}

// Toggle topic filter chip
function toggleTopicFilter(topicId, topicName) {
    const chip = document.querySelector(`[data-topic-id="${topicId}"]`);
    if (!chip) return;
    
    if (chip.classList.contains('active')) {
        chip.classList.remove('active');
        removeActiveFilter(`topic-${topicId}`);
    } else {
        chip.classList.add('active');
        addActiveFilter(`topic-${topicId}`, topicId, `Topic: ${topicName}`);
    }
    applyFilters();
}

// Filter by subject from category cards
function filterBySubject(subjectName) {
    const subjectSelect = document.getElementById('filter-subject');
    if (subjectSelect) {
        subjectSelect.value = subjectName;
        addActiveFilter('filter-subject', subjectName, subjectName);
        applyFilters();
    }
}

// Add active filter
function addActiveFilter(key, value, displayText) {
    activeFilters[key] = { value, displayText };
    renderActiveFilters();
}

// Remove active filter
function removeActiveFilter(key) {
    delete activeFilters[key];
    renderActiveFilters();
    
    // Update UI elements
    if (key === 'search') {
        const searchInput = document.getElementById('search-notes');
        if (searchInput) searchInput.value = '';
    } else if (key.startsWith('topic-')) {
        const topicId = key.replace('topic-', '');
        const chip = document.querySelector(`[data-topic-id="${topicId}"]`);
        if (chip) chip.classList.remove('active');
    } else {
        const element = document.getElementById(key);
        if (element) element.value = '';
    }
}

// Render active filters
function renderActiveFilters() {
    const container = document.getElementById('active-filters');
    const filtersList = document.getElementById('filters-list');
    
    if (!container || !filtersList) return;
    
    const filterKeys = Object.keys(activeFilters);
    
    if (filterKeys.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'flex';
    filtersList.innerHTML = filterKeys.map(key => `
        <span class="filter-chip">
            ${activeFilters[key].displayText}
            <button class="remove-filter" onclick="removeActiveFilter('${key}')">×</button>
        </span>
    `).join('');
}

// Clear all filters
function clearAllFilters() {
    activeFilters = {};
    
    // Reset all filter controls
    document.getElementById('search-notes').value = '';
    document.getElementById('filter-subject').value = '';
    document.getElementById('filter-topic').value = '';
    document.getElementById('filter-difficulty').value = '';
    
    // Reset topic chips
    document.querySelectorAll('.topic-chip.active').forEach(chip => {
        chip.classList.remove('active');
    });
    
    renderActiveFilters();
    applyFilters();
}

// Apply filters to notes
function applyFilters() {
    // This would typically make an API call to fetch filtered notes
    console.log('Applying filters:', activeFilters);
    
    // For now, just update the notes display
    if (typeof loadNotes === 'function') {
        loadNotes(activeFilters);
    }
}

// Setup category navigation
function setupCategoryNavigation() {
    const viewToggle = document.querySelectorAll('.toggle-btn');
    viewToggle.forEach(btn => {
        btn.addEventListener('click', function() {
            viewToggle.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.dataset.view;
            const grid = document.getElementById('categories-grid');
            if (grid) {
                grid.className = `categories-${view}`;
            }
        });
    });
}

// Update stats display
function updateStats(stats) {
    const elements = {
        'total-notes': stats.total_notes,
        'total-topics': stats.total_topics,
        'total-subjects': stats.total_subjects,
        'contributors': stats.contributors
    };
    
    Object.keys(elements).forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            animateNumber(element, elements[id]);
        }
    });
}

// Animate number change
function animateNumber(element, targetValue) {
    const startValue = parseInt(element.textContent) || 0;
    const duration = 1000;
    const stepTime = 50;
    const steps = duration / stepTime;
    const stepValue = (targetValue - startValue) / steps;
    
    let currentValue = startValue;
    const timer = setInterval(() => {
        currentValue += stepValue;
        element.textContent = Math.round(currentValue);
        
        if (Math.abs(currentValue - targetValue) < Math.abs(stepValue)) {
            element.textContent = targetValue;
            clearInterval(timer);
        }
    }, stepTime);
}

// Utility function for debouncing
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Enhanced upload form submission
function setupEnhancedUploadForm() {
    const form = document.getElementById('upload-form');
    if (!form) return;
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Get form data including new fields
        const formData = new FormData();
        formData.append('title', document.getElementById('note-title').value);
        formData.append('subject', document.getElementById('note-subject').value);
        formData.append('difficulty', document.getElementById('note-difficulty').value);
        formData.append('description', document.getElementById('note-description').value);
        formData.append('tags', document.getElementById('note-tags').value);
        formData.append('topics', JSON.stringify(selectedTopics));
        formData.append('file', document.getElementById('note-file').files[0]);
        
        try {
            const response = await fetch('../api/student/uploadNotes.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Reset form and reload data
                form.reset();
                selectedTopics = [];
                renderSelectedTopics();
                loadCategoriesData();
                
                if (typeof showAlert === 'function') {
                    showAlert('Notes uploaded successfully!', 'success');
                }
            } else {
                if (typeof showAlert === 'function') {
                    showAlert(result.error || 'Upload failed', 'error');
                }
            }
        } catch (error) {
            console.error('Upload error:', error);
            if (typeof showAlert === 'function') {
                showAlert('Upload failed. Please try again.', 'error');
            }
        }
    });
}

// Initialize enhanced upload form
document.addEventListener('DOMContentLoaded', setupEnhancedUploadForm);