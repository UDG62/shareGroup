// Génération d'une couleur aléatoire en format hexadécimal
function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

// Initialisation de la question du CAPTCHA
let correctColor = getRandomColor();
const colorOptions = [correctColor, getRandomColor(), getRandomColor()];
shuffleArray(colorOptions);  // Mélange des options

// Désactiver le bouton d'envoi initialement
const btEnvoyer = document.getElementById("btEnvoyer");
btEnvoyer.disabled = true;

// Afficher la question avec la couleur
function displayCaptchaQuestion() {
    const questionElement = document.getElementById("captchaQuestion");
    questionElement.innerHTML = `Question : Cliquez sur la couleur <span class="color-display" style="background-color: ${correctColor}">__________</span>`;
}

displayCaptchaQuestion();

// Afficher les options de couleur
const colorOptionsContainer = document.getElementById("colorOptions");
colorOptions.forEach(color => {
    const colorDiv = document.createElement('div');
    colorDiv.classList.add('captcha-color');
    colorDiv.style.backgroundColor = color;
    colorDiv.onclick = () => checkCaptcha(color);
    colorOptionsContainer.appendChild(colorDiv);
});

// Fonction pour vérifier si la couleur sélectionnée est correcte
function checkCaptcha(selectedColor) {
    const resultElement = document.getElementById("captchaResult");

    if (selectedColor === correctColor) {
        resultElement.textContent = "CAPTCHA réussi !";
        resultElement.style.color = "green";
        btEnvoyer.disabled = false;  // Active le bouton "Se connecter" si le CAPTCHA est correct
    } else {
        resultElement.textContent = "CAPTCHA échoué. Réessayez.";
        resultElement.style.color = "red";
        btEnvoyer.disabled = true; // Le bouton reste désactivé si le CAPTCHA échoue
        resetCaptcha();
    }
}

// Mélange aléatoire des options
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}

// Réinitialiser le CAPTCHA avec une nouvelle couleur
function resetCaptcha() {
    correctColor = getRandomColor();
    const newColors = [correctColor, getRandomColor(), getRandomColor()];
    shuffleArray(newColors);

    displayCaptchaQuestion();
    colorOptionsContainer.innerHTML = '';

    newColors.forEach(color => {
        const colorDiv = document.createElement('div');
        colorDiv.classList.add('captcha-color');
        colorDiv.style.backgroundColor = color;
        colorDiv.onclick = () => checkCaptcha(color);
        colorOptionsContainer.appendChild(colorDiv);
    });

    document.getElementById("captchaResult").textContent = '';
    btEnvoyer.disabled = true;  // Assure que le bouton est désactivé après chaque échec du CAPTCHA
}