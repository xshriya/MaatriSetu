// Wait for the page to be fully loaded
window.addEventListener('DOMContentLoaded', () => {

    const eligibilityForm = document.getElementById('eligibility-form');
    const schemeCards = document.querySelectorAll('.scheme-card');
    const resultsContainer = document.getElementById('results-container');
    const resultsContent = document.getElementById('results-content'); // Get the new content area
    const resetBtn = document.getElementById('reset-btn'); // Get the new reset button

    // Check if all necessary elements exist on the page
    if (eligibilityForm && schemeCards.length > 0 && resultsContainer && resetBtn) {
        
        // --- EVENT LISTENER FOR THE SUBMIT BUTTON ---
        eligibilityForm.addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent the page from reloading

            const formData = new FormData(eligibilityForm);
            const isFirstChild = formData.get('firstChild') === 'yes';
            const isBpl = formData.get('bpl') === 'yes';
            
            let eligibleSchemes = [];
            
            schemeCards.forEach(card => {
                const schemeName = card.querySelector('h3').textContent;
                const requiresFirstChild = card.dataset.firstChild === 'true';
                const requiresBpl = card.dataset.bpl === 'true';
                let isEligible = true;

                if (requiresFirstChild && !isFirstChild) isEligible = false;
                if (requiresBpl && !isBpl) isEligible = false;

                if (isEligible) {
                    card.classList.add('highlight');
                    card.classList.remove('faded');
                    eligibleSchemes.push(schemeName);
                } else {
                    card.classList.add('faded');
                    card.classList.remove('highlight');
                }
            });

            // Build and display the results message
            let resultsHTML = '';
            if (eligibleSchemes.length > 0) {
                resultsHTML += '<h4 class="font-bold text-green-700 mb-2">Based on your answers, you are likely eligible for:</h4>';
                resultsHTML += '<ul class="list-disc list-inside text-green-800 space-y-1">';
                eligibleSchemes.forEach(name => {
                    resultsHTML += `<li>${name}</li>`;
                });
                resultsHTML += '</ul>';
            } else {
                resultsHTML += '<p class="font-bold text-gray-700">Based on your answers, you may not be eligible for the schemes with specific criteria, but you can still access general programs like Kilkari and SUMAN.</p>';
            }

            resultsContent.innerHTML = resultsHTML; // Put message in the content div
            resultsContainer.classList.remove('hidden'); // Show the entire results container (text + button)
        });

        // --- NEW: EVENT LISTENER FOR THE RESET BUTTON ---
        resetBtn.addEventListener('click', () => {
            // 1. Clear the form selections
            eligibilityForm.reset();

            // 2. Hide the results container
            resultsContainer.classList.add('hidden');

            // 3. Reset the styles of all scheme cards
            schemeCards.forEach(card => {
                card.classList.remove('highlight');
                card.classList.remove('faded');
            });
        });
    }
});