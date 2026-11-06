
// ==================================
// KEYBOARD NINJA - TYPING GAME
// ==================================

const passages = [
    "Practice makes a person perfect. Keep learning and improving your skills every day.",
    "A great programmer is someone who always looks for better solutions to problems.",
    "Typing fast requires patience, concentration and regular practice. Never give up.",
    "Success comes to those who work hard and stay focused on their goals.",
    "Technology makes our lives easier and helps us connect with the world."
];

const passageElement = document.getElementById("passage");
const inputElement = document.getElementById("typingInput");
const timerElement = document.getElementById("timer");
const wpmElement = document.getElementById("wpm");
const accuracyElement = document.getElementById("accuracy");
const errorsElement = document.getElementById("errors");
const durationElement = document.getElementById("duration");
const startButton = document.getElementById("startBtn");
const resetButton = document.getElementById("resetBtn");
const resultBox = document.getElementById("result");
const resultMessage = document.getElementById("resultMessage");

let selectedPassage = "";
let timeLeft = 60;
let timerInterval = null;
let startTime = null;
let gameRunning = false;


// SELECT RANDOM PASSAGE

function choosePassage() {
    const randomIndex = Math.floor(Math.random() * passages.length);
    selectedPassage = passages[randomIndex];

    displayPassage("");
}


// DISPLAY PASSAGE

function displayPassage(typedText) {

    passageElement.innerHTML = "";

    selectedPassage.split("").forEach((character, index) => {

        const span = document.createElement("span");
        span.textContent = character;

        if (index < typedText.length) {

            if (typedText[index] === character) {
                span.classList.add("correct");
            } else {
                span.classList.add("wrong");
            }

        } else if (index === typedText.length) {

            span.classList.add("current");
        }

        passageElement.appendChild(span);

    });
}


// UPDATE STATISTICS

function updateStats() {

    const typedText = inputElement.value;

    let correctCharacters = 0;
    let errors = 0;

    for (let i = 0; i < typedText.length; i++) {

        if (typedText[i] === selectedPassage[i]) {
            correctCharacters++;
        } else {
            errors++;
        }
    }

    const elapsedSeconds = startTime
        ? Math.max(1, (Date.now() - startTime) / 1000)
        : 1;

    const minutes = elapsedSeconds / 60;

    const wpm = Math.round(
        (correctCharacters / 5) / minutes
    );

    const accuracy = typedText.length > 0
        ? Math.round((correctCharacters / typedText.length) * 100)
        : 100;

    wpmElement.textContent = wpm;
    accuracyElement.textContent = accuracy + "%";
    errorsElement.textContent = errors;

    displayPassage(typedText);
}


// START GAME

function startGame() {

    clearInterval(timerInterval);

    choosePassage();

    timeLeft = Number(durationElement.value);

    timerElement.textContent = timeLeft + "s";

    inputElement.value = "";
    inputElement.disabled = false;
    inputElement.maxLength = selectedPassage.length;

    durationElement.disabled = true;
    startButton.disabled = true;

    resultBox.style.display = "none";

    wpmElement.textContent = "0";
    accuracyElement.textContent = "100%";
    errorsElement.textContent = "0";

    gameRunning = true;
    startTime = Date.now();

    inputElement.focus();

    timerInterval = setInterval(() => {

        timeLeft--;

        timerElement.textContent = timeLeft + "s";

        if (timeLeft <= 0) {
            finishGame();
        }

    }, 1000);
}


// FINISH GAME



function finishGame() {

    if (!gameRunning) return;

    gameRunning = false;

    clearInterval(timerInterval);

    inputElement.disabled = true;
    durationElement.disabled = false;
    startButton.disabled = false;

    updateStats();

    const finalWPM = Number(wpmElement.textContent);
    const finalAccuracy = Number(
        accuracyElement.textContent.replace("%", "")
    );
    const finalErrors = Number(errorsElement.textContent);
    const testDuration = Number(durationElement.value);

    resultMessage.textContent =
        `Your typing speed: ${finalWPM} WPM | ` +
        `Accuracy: ${finalAccuracy}% | ` +
        `Errors: ${finalErrors}. Keep practising!`;

    resultBox.style.display = "block";

    const saveStatus = document.getElementById("saveStatus");

    if (!saveStatus) {
        console.error("saveStatus element is missing from HTML.");
        return;
    }

    saveStatus.textContent = "⏳ Saving your score...";

    console.log("Sending score:", {
        wpm: finalWPM,
        accuracy: finalAccuracy,
        errors: finalErrors,
        duration: testDuration
    });

    fetch("save-score.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            wpm: finalWPM,
            accuracy: finalAccuracy,
            errors: finalErrors,
            duration: testDuration
        })
    })

    .then(async response => {

        const responseText = await response.text();

        console.log("PHP response status:", response.status);
        console.log("PHP response:", responseText);

        let data;

        try {
            data = JSON.parse(responseText);
        } catch (error) {
            throw new Error(
                "PHP returned invalid JSON: " + responseText
            );
        }

        if (!response.ok) {
            throw new Error(data.message || "Server error");
        }

        return data;
    })

    .then(data => {

        if (data.success) {

            saveStatus.textContent =
                "✅ " + data.message;

            console.log("Score saved! ID:", data.result_id);

        } else {

            saveStatus.textContent =
                "❌ " + data.message;

        }

    })

    .catch(error => {

        console.error("Score saving failed:", error);

        saveStatus.textContent =
            "❌ " + error.message;

    });

    resultBox.scrollIntoView({
        behavior: "smooth",
        block: "nearest"
    });
}



// RESET GAME

function resetGame() {

    clearInterval(timerInterval);

    gameRunning = false;
    startTime = null;

    timeLeft = Number(durationElement.value);

    timerElement.textContent = timeLeft + "s";

    inputElement.value = "";
    inputElement.disabled = true;

    durationElement.disabled = false;
    startButton.disabled = false;

    wpmElement.textContent = "0";
    accuracyElement.textContent = "100%";
    errorsElement.textContent = "0";

    resultBox.style.display = "none";

    choosePassage();
}


// TYPING EVENT

inputElement.addEventListener("input", () => {

    if (!gameRunning) return;

    updateStats();

    if (inputElement.value.length >= selectedPassage.length) {
        finishGame();
    }
});


// BUTTON EVENTS

startButton.addEventListener("click", startGame);

resetButton.addEventListener("click", resetGame);


// INITIALIZE GAME

choosePassage();

timerElement.textContent = "60s";
