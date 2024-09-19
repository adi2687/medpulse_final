
document.addEventListener('DOMContentLoaded', async () => {
    console.log('DOM fully loaded and parsed');

    try {
        const response = await fetch('prediction.json'); 
        if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.statusText}`);
        }
        
        const diseaseD = await response.json();
        console.log(diseaseD);

        const diseaseMap = new Map();
        diseaseD.diseaseData.forEach(disease => {
            diseaseMap.set(disease.name, {
                symptoms: disease.symptoms,
                cure: disease.cure
            });
        });

        const popup = document.querySelector('.popup');
        const overlay = document.querySelector('.overlay');
        const diseaseNameElem = document.querySelector('.popup .disease-name');
        const diseaseSymptomsElem = document.querySelector('.popup .disease-symptoms');
        const diseaseCureElem = document.querySelector('.popup .disease-cure');
        const closeBtn = document.querySelector('.popup .close');

        function showPopup(diseaseName, symptoms, cure) {
            diseaseNameElem.textContent = diseaseName;
            diseaseSymptomsElem.textContent = symptoms.join(', ');
            diseaseCureElem.textContent = cure;
            popup.classList.add('visible');
            overlay.classList.add('visible');
        }

        function hidePopup() {
            popup.classList.remove('visible');
            overlay.classList.remove('visible');
        }

        const diseaseItems = document.querySelectorAll(".disease-box, ul li");
        diseaseItems.forEach(item => {
            item.addEventListener("click", () => {
                const diseaseName = item.textContent.trim();
                if (diseaseMap.has(diseaseName)) {
                    const { symptoms, cure } = diseaseMap.get(diseaseName);
                    showPopup(diseaseName, symptoms, cure);
                } else {
                    console.log("Disease data not found");
                }
            });
        });

        closeBtn.addEventListener('click', hidePopup);
        overlay.addEventListener('click', hidePopup);

    } catch (error) {
        console.error('Error fetching JSON data:', error);
    }
});
