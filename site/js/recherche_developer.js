// Initialize tooltips and toasts
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entrée
    setTimeout(() => {
        document.querySelectorAll('.fade-in').forEach((el, index) => {
            el.style.animationDelay = `${index * 0.2}s`;
        });
    }, 100);
});

// Search form handler
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    performSearch();
});

function performSearch() {
    const searchBtn = document.querySelector('.btn-search');
    const searchText = searchBtn.querySelector('.search-text');
    const loadingSpinner = searchBtn.querySelector('.loading-spinner');
    
    // Show loading state
    searchText.style.display = 'none';
    loadingSpinner.style.display = 'inline';
    searchBtn.disabled = true;

    const onduleur = document.getElementById('onduleur').value;
    const panneaux = document.getElementById('panneaux').value;
    const departement = document.getElementById('departement').value;

    // Simulate search delay
    setTimeout(() => {
        // Hide loading state
        searchText.style.display = 'inline';
        loadingSpinner.style.display = 'none';
        searchBtn.disabled = false;
        
        // Update results
        updateResults(onduleur, panneaux, departement);
    }, 2000);
}

function updateResults(onduleur, panneaux, departement) {
    const container = document.getElementById('resultsContainer');
    const mockData = [
        {
            title: `Installation ${onduleur || 'SMA'} - ${panneaux || 'SunPower'}`,
            details: `15/03/2024 - 12 panneaux ${panneaux || 'SunPower'} - 45m² - 5.2kW - ${departement || '29'} Finistère`,
            icon: 'bi-lightning-charge'
        },
        {
            title: `Installation ${onduleur || 'Fronius'} - ${panneaux || 'LG'}`,
            details: `22/02/2024 - 8 panneaux ${panneaux || 'LG'} - 32m² - 3.8kW - ${departement || '35'} Ille-et-Vilaine`,
            icon: 'bi-sun'
        },
        {
            title: `Installation ${onduleur || 'Huawei'} - ${panneaux || 'Jinko'}`,
            details: `08/01/2024 - 16 panneaux ${panneaux || 'Jinko'} - 56m² - 6.4kW - ${departement || '56'} Morbihan`,
            icon: 'bi-battery-charging'
        }
    ];

    container.innerHTML = '';
    
    mockData.forEach((data, index) => {
        const resultItem = document.createElement('div');
        resultItem.className = 'result-item mb-3';
        resultItem.onclick = () => selectResult(resultItem);
        resultItem.style.opacity = '0';
        resultItem.style.transform = 'translateY(20px)';
        
        resultItem.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi ${data.icon} me-3 text-primary fs-4"></i>
                <div class="flex-grow-1">
                    <strong>${data.title}</strong><br>
                    <small class="text-muted">${data.details}</small>
                </div>
                <button class="tn btn-primary btn-search btn-lg">
                    <small>
                        Modifier
                    </small>
                </button>
                <button class="tn btn-primary btn-search btn-lg">
                    <small>
                        Supprimer
                    </small>
                </button>
                <i class="bi bi-chevron-right text-muted"></i>
            </div>
        `;
        
        container.appendChild(resultItem);
        
        // Animate appearance
        setTimeout(() => {
            resultItem.style.transition = 'all 0.5s ease';
            resultItem.style.opacity = '1';
            resultItem.style.transform = 'translateY(0)';
        }, index * 200);
    });
}

function selectResult(element) {
    // Remove previous selection
    document.querySelectorAll('.result-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Add selection
    element.classList.add('selected');
    
    setTimeout(() => {
        element.classList.remove('selected');
        const title = element.querySelector('strong').textContent;
    }, 800);
}