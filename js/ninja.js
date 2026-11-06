
// ======================================
// KEYBOARD NINJA - ARCADE GAME + MYSQL
// ======================================

const arena = document.getElementById("gameArena");
const fallingWords = document.getElementById("fallingWords");
const ninjaInput = document.getElementById("ninjaInput");

const scoreDisplay = document.getElementById("ninjaScore");
const levelDisplay = document.getElementById("ninjaLevel");
const livesDisplay = document.getElementById("ninjaLives");
const timeDisplay = document.getElementById("ninjaTime");

const arenaMessage = document.getElementById("arenaMessage");
const startButton = document.getElementById("ninjaStartBtn");
const restartButton = document.getElementById("ninjaRestartBtn");

const resultBox = document.getElementById("ninjaResult");
const finalScore = document.getElementById("ninjaFinalScore");
const saveStatus = document.getElementById("ninjaSaveStatus");

const words = [
    "code", "ninja", "keyboard", "javascript",
    "python", "computer", "student", "gaming",
    "focus", "speed", "challenge", "program",
    "website", "database", "function", "logic",
    "design", "future", "technology", "success",
    "developer", "internet", "creative", "project"
];

let score = 0;
let lives = 3;
let level = 1;
let seconds = 0;
let missedWords = 0;

let gameActive = false;
let scoreSaved = false;

let wordObjects = [];

let spawnInterval = null;
let gameInterval = null;
let clockInterval = null;

const wordSpeed = 0.7;
const spawnDelay = 1700;


// ======================================
// START GAME
// ======================================

function startNinjaGame() {

    clearInterval(spawnInterval);
    clearInterval(gameInterval);
    clearInterval(clockInterval);

    score = 0;
    lives = 3;
    level = 1;
    seconds = 0;
    missedWords = 0;

    scoreSaved = false;

    wordObjects = [];

    fallingWords.innerHTML = "";

    gameActive = true;

    arenaMessage.style.display = "none";
    resultBox.style.display = "none";

    ninjaInput.disabled = false;
    ninjaInput.value = "";
    ninjaInput.focus();

    updateDashboard();

    spawnInterval = setInterval(spawnWord, spawnDelay);

    gameInterval = setInterval(moveWords, 30);

    clockInterval = setInterval(() => {

        seconds++;

        level = Math.floor(score / 50) + 1;

        updateDashboard();

    }, 1000);

}


// ======================================
// CREATE FALLING WORD
// ======================================

function spawnWord() {

    if (!gameActive) return;

    const word = words[
        Math.floor(Math.random() * words.length)
    ];

    const element = document.createElement("div");

    element.className = "falling-word";
    element.textContent = word;

    const maxLeft = Math.max(0, arena.clientWidth - 130);

    const left = Math.random() * maxLeft;

    element.style.left = left + "px";
    element.style.transform = "none";

    fallingWords.appendChild(element);

    wordObjects.push({

        text: word,
        element: element,
        top: 0,
        speed: wordSpeed + (level - 1) * 0.15

    });

}


// ======================================
// MOVE WORDS
// ======================================

function moveWords() {

    if (!gameActive) return;

    // Reverse loop prevents skipped words when removing items

    for (let i = wordObjects.length - 1; i >= 0; i--) {

        const obj = wordObjects[i];

        obj.top += obj.speed;

        obj.element.style.top = obj.top + "px";

        if (obj.top >= arena.clientHeight - 40) {

            obj.element.remove();

            wordObjects.splice(i, 1);

            loseLife();

            if (!gameActive) return;

        }

    }

}


// ======================================
// CHECK TYPED WORD
// ======================================

ninjaInput.addEventListener("input", () => {

    if (!gameActive) return;

    const typed = ninjaInput.value.trim().toLowerCase();

    const matchIndex = wordObjects.findIndex(
        obj => obj.text === typed
    );

    if (matchIndex !== -1) {

        const matchedWord = wordObjects[matchIndex];

        matchedWord.element.classList.add("matched");

        setTimeout(() => {
            matchedWord.element.remove();
        }, 150);

        wordObjects.splice(matchIndex, 1);

        score += 10;

        ninjaInput.value = "";

        level = Math.floor(score / 50) + 1;

        updateDashboard();

    }

});


// ======================================
// LOSE LIFE
// ======================================

function loseLife() {

    if (!gameActive) return;

    lives--;

    missedWords++;

    updateDashboard();

    if (lives <= 0) {

        endNinjaGame();

    }

}


// ======================================
// UPDATE DASHBOARD
// ======================================

function updateDashboard() {

    scoreDisplay.textContent = score;

    levelDisplay.textContent = level;

    livesDisplay.textContent =
        "❤️ ".repeat(lives) +
        "🖤 ".repeat(3 - lives).trim();

    timeDisplay.textContent = seconds + "s";

}


// ======================================
// END GAME
// ======================================

function endNinjaGame() {

    if (!gameActive) return;

    gameActive = false;

    clearInterval(spawnInterval);
    clearInterval(gameInterval);
    clearInterval(clockInterval);

    ninjaInput.disabled = true;

    finalScore.textContent =
        `Your score: ${score} | ` +
        `Level reached: ${level} | ` +
        `Time survived: ${seconds}s`;

    resultBox.style.display = "block";

    arenaMessage.style.display = "none";

    saveNinjaScore();

}


// ======================================
// SAVE SCORE TO MYSQL
// ======================================

async function saveNinjaScore() {

    if (scoreSaved) return;

    scoreSaved = true;

    saveStatus.textContent = "⏳ Saving your Ninja score...";

    const gameData = {

        score: score,
        level: level,
        duration: seconds,
        errors: missedWords

    };

    console.log("Sending Ninja score:", gameData);

    try {

        const response = await fetch("save-ninja-score.php", {

            method: "POST",

            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify(gameData)

        });

        const responseText = await response.text();

        console.log("Ninja PHP response:", responseText);

        let data;

        try {

            data = JSON.parse(responseText);

        } catch (error) {

            throw new Error("Invalid PHP response: " + responseText);

        }

        if (!response.ok || !data.success) {

            throw new Error(
                data.message || "Unable to save Ninja score."
            );

        }

        saveStatus.textContent = "✅ " + data.message;

        console.log("Ninja score saved! ID:", data.result_id);

    } catch (error) {

        console.error("Ninja score saving failed:", error);

        saveStatus.textContent = "❌ " + error.message;

    }

}


// ======================================
// BUTTON EVENTS
// ======================================

startButton.addEventListener("click", startNinjaGame);

restartButton.addEventListener("click", startNinjaGame);
