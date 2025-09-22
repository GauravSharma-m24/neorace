<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoRace - Advanced Car Racing</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a1a 50%, #0f0f0f 100%);
            color: #fff;
            overflow: hidden;
            height: 100vh;
        }

        .game-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }

        .start-screen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #feca57);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .game-title {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-shadow: 0 0 30px rgba(255,255,255,0.8);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .car-selection {
            display: flex;
            gap: 2rem;
            margin: 2rem 0;
        }

        .car-option {
            padding: 1rem 2rem;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }

        .car-option:hover, .car-option.selected {
            border-color: #fff;
            background: rgba(255,255,255,0.2);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .start-btn {
            padding: 1rem 3rem;
            font-size: 1.5rem;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            border: none;
            border-radius: 50px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 2rem;
            text-transform: uppercase;
            font-weight: bold;
        }

        .start-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255,107,107,0.4);
        }

        .game-area {
            position: relative;
            width: 100%;
            height: 100vh;
            background: linear-gradient(to bottom, #87CEEB 0%, #87CEEB 30%, #90EE90 30%, #90EE90 100%);
            overflow: hidden;
            display: none;
        }

        .road {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 400px;
            height: 100vh;
            background: #333;
            border-left: 5px solid #fff;
            border-right: 5px solid #fff;
        }

        .road-markings {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 10px;
            height: 100vh;
            background: repeating-linear-gradient(
                to bottom,
                #fff 0px,
                #fff 40px,
                transparent 40px,
                transparent 80px
            );
            animation: roadMove 0.5s linear infinite;
        }

        @keyframes roadMove {
            0% { transform: translateX(-50%) translateY(-80px); }
            100% { transform: translateX(-50%) translateY(0px); }
        }

        .player-car {
            position: absolute;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 120px;
            background: url('https://cdn-icons-png.flaticon.com/512/3774/3774278.png') no-repeat center;
            background-size: contain;
            transition: left 0.1s ease;
            z-index: 100;
        }

        .enemy-car {
            position: absolute;
            width: 50px;
            height: 100px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        .hud {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 200;
            background: rgba(0,0,0,0.7);
            padding: 20px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .speed-meter {
            margin-bottom: 10px;
        }

        .speed-bar {
            width: 200px;
            height: 10px;
            background: #333;
            border-radius: 5px;
            overflow: hidden;
        }

        .speed-fill {
            height: 100%;
            background: linear-gradient(90deg, #4ecdc4, #ff6b6b);
            width: 0%;
            transition: width 0.1s ease;
        }

        .score {
            font-size: 1.2rem;
            margin-top: 10px;
        }

        .game-over {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: rgba(0,0,0,0.9);
            padding: 3rem;
            border-radius: 20px;
            display: none;
            z-index: 300;
            backdrop-filter: blur(20px);
        }

        .controls-hint {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: rgba(0,0,0,0.7);
            padding: 15px;
            border-radius: 10px;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
        }

        .restart-btn {
            padding: 1rem 2rem;
            background: linear-gradient(45deg, #4ecdc4, #45b7d1);
            border: none;
            border-radius: 25px;
            color: white;
            cursor: pointer;
            font-size: 1.1rem;
            margin-top: 1rem;
        }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: #fff;
            border-radius: 50%;
            animation: particleMove 3s linear infinite;
        }

        @keyframes particleMove {
            0% {
                transform: translateY(-10px);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(100vh);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <div class="game-container">
        <div class="start-screen" id="startScreen">
            <h1 class="game-title">🏎️ NEORACE</h1>
            <p style="font-size: 1.2rem; margin-bottom: 2rem;">Choose Your Racing Machine</p>
            
            <div class="car-selection">
                <div class="car-option selected" data-car="sports">
                    <h3>🏎️ Sports Car</h3>
                    <p>Speed: ⭐⭐⭐⭐⭐</p>
                </div>
                <div class="car-option" data-car="muscle">
                    <h3>🚗 Muscle Car</h3>
                    <p>Power: ⭐⭐⭐⭐</p>
                </div>
                <div class="car-option" data-car="supercar">
                    <h3>🏁 Supercar</h3>
                    <p>Ultimate: ⭐⭐⭐⭐⭐</p>
                </div>
            </div>
            
            <button class="start-btn" onclick="startGame()">START RACING</button>
        </div>

        <div class="game-area" id="gameArea">
            <div class="particles" id="particles"></div>
            <div class="road"></div>
            <div class="road-markings"></div>
            <div class="player-car" id="playerCar"></div>
            
            <div class="hud">
                <div class="speed-meter">
                    <div>Speed</div>
                    <div class="speed-bar">
                        <div class="speed-fill" id="speedFill"></div>
                    </div>
                </div>
                <div class="score">Score: <span id="score">0</span></div>
                <div>Distance: <span id="distance">0</span>m</div>
            </div>
            
            <div class="controls-hint">
                <div><strong>Controls:</strong></div>
                <div>← → Arrow Keys or A/D</div>
                <div>↑ ↓ for Speed</div>
            </div>
        </div>

        <div class="game-over" id="gameOver">
            <h2 style="color: #ff6b6b; margin-bottom: 1rem;">GAME OVER!</h2>
            <div style="font-size: 1.2rem;">Final Score: <span id="finalScore">0</span></div>
            <div style="font-size: 1.1rem;">Distance: <span id="finalDistance">0</span>m</div>
            <button class="restart-btn" onclick="restartGame()">RACE AGAIN</button>
        </div>
    </div>

    <script>
        // Game state
        let gameState = {
            isRunning: false,
            score: 0,
            distance: 0,
            speed: 0,
            maxSpeed: 100,
            playerX: 0,
            enemyCars: [],
            keys: {},
            selectedCar: 'sports'
        };

        // Car configurations
        const carConfigs = {
            sports: {
                image: 'https://cdn-icons-png.flaticon.com/512/3774/3774278.png',
                maxSpeed: 120,
                acceleration: 1.2
            },
            muscle: {
                image: 'https://cdn-icons-png.flaticon.com/512/741/741407.png',
                maxSpeed: 100,
                acceleration: 1.5
            },
            supercar: {
                image: 'https://cdn-icons-png.flaticon.com/512/3774/3774299.png',
                maxSpeed: 140,
                acceleration: 1.0
            }
        };

        // Enemy car images
        const enemyCarImages = [
            'https://cdn-icons-png.flaticon.com/512/741/741415.png',
            'https://cdn-icons-png.flaticon.com/512/741/741407.png',
            'https://cdn-icons-png.flaticon.com/512/3774/3774370.png',
            'https://cdn-icons-png.flaticon.com/512/3774/3774299.png'
        ];

        // Initialize game
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            createParticles();
        });

        function setupEventListeners() {
            // Car selection
            document.querySelectorAll('.car-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.car-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    gameState.selectedCar = this.dataset.car;
                });
            });

            // Keyboard controls
            document.addEventListener('keydown', function(e) {
                gameState.keys[e.code] = true;
                
                if (gameState.isRunning) {
                    switch(e.code) {
                        case 'ArrowLeft':
                        case 'KeyA':
                            movePlayer(-1);
                            break;
                        case 'ArrowRight':
                        case 'KeyD':
                            movePlayer(1);
                            break;
                        case 'ArrowUp':
                        case 'KeyW':
                            accelerate();
                            break;
                        case 'ArrowDown':
                        case 'KeyS':
                            brake();
                            break;
                    }
                }
            });

            document.addEventListener('keyup', function(e) {
                gameState.keys[e.code] = false;
            });
        }

        function startGame() {
            document.getElementById('startScreen').style.display = 'none';
            document.getElementById('gameArea').style.display = 'block';
            
            // Set player car image
            const playerCar = document.getElementById('playerCar');
            playerCar.style.backgroundImage = `url('${carConfigs[gameState.selectedCar].image}')`;
            
            gameState.isRunning = true;
            gameState.score = 0;
            gameState.distance = 0;
            gameState.speed = 30;
            gameState.playerX = 0;
            gameState.enemyCars = [];
            gameState.maxSpeed = carConfigs[gameState.selectedCar].maxSpeed;
            
            // Start game loop
            gameLoop();
            spawnEnemyCars();
        }

        function gameLoop() {
            if (!gameState.isRunning) return;

            updateGame();
            updateHUD();
            checkCollisions();
            
            requestAnimationFrame(gameLoop);
        }

        function updateGame() {
            // Update score and distance
            gameState.score += Math.floor(gameState.speed / 10);
            gameState.distance += Math.floor(gameState.speed / 10);
            
            // Move enemy cars
            gameState.enemyCars.forEach((car, index) => {
                car.y += gameState.speed / 3;
                car.element.style.top = car.y + 'px';
                
                // Remove cars that are off screen
                if (car.y > window.innerHeight) {
                    car.element.remove();
                    gameState.enemyCars.splice(index, 1);
                    gameState.score += 100; // Bonus for avoiding car
                }
            });
            
            // Increase speed gradually
            if (gameState.speed < gameState.maxSpeed && Math.random() < 0.005) {
                gameState.speed += 0.3;
            }
            
            // Update road animation speed
            const roadMarkings = document.querySelector('.road-markings');
            roadMarkings.style.animationDuration = (1 - gameState.speed / gameState.maxSpeed) * 1.2 + 0.6 + 's';
        }

        function updateHUD() {
            document.getElementById('score').textContent = gameState.score;
            document.getElementById('distance').textContent = gameState.distance;
            document.getElementById('speedFill').style.width = (gameState.speed / gameState.maxSpeed * 100) + '%';
        }

        function movePlayer(direction) {
            const playerCar = document.getElementById('playerCar');
            const roadWidth = 700;
            const carWidth = 60;
            const maxX = (roadWidth - carWidth) / 2;
            
            gameState.playerX += direction * 20;
            gameState.playerX = Math.max(-maxX, Math.min(maxX, gameState.playerX));
            
            playerCar.style.left = `calc(50% + ${gameState.playerX}px)`;
            
            // Add tilt effect
            playerCar.style.transform = `translateX(-50%) rotate(${direction * 5}deg)`;
            setTimeout(() => {
                playerCar.style.transform = 'translateX(-50%) rotate(0deg)';
            }, 200);
        }

        function accelerate() {
            if (gameState.speed < gameState.maxSpeed) {
                gameState.speed += carConfigs[gameState.selectedCar].acceleration;
            }
        }

        function brake() {
            if (gameState.speed > 20) {
                gameState.speed -= 3;
            }
        }

        function spawnEnemyCars() {
            if (!gameState.isRunning) return;
            
            const gameArea = document.getElementById('gameArea');
            const enemyCar = document.createElement('div');
            enemyCar.className = 'enemy-car';
            
            // Random position on road
            const roadWidth = 400;
            const carWidth = 50;
            const randomX = Math.random() * (roadWidth - carWidth) - (roadWidth - carWidth) / 2;
            
            enemyCar.style.left = `calc(50% + ${randomX}px)`;
            enemyCar.style.top = '-100px';
            enemyCar.style.backgroundImage = `url('${enemyCarImages[Math.floor(Math.random() * enemyCarImages.length)]}')`;
            
            gameArea.appendChild(enemyCar);
            
            gameState.enemyCars.push({
                element: enemyCar,
                x: randomX,
                y: -100
            });
            
            // Spawn next car
            const spawnDelay = Math.max(800, 2500 - gameState.speed * 5);
            setTimeout(spawnEnemyCars, spawnDelay);
        }

        function checkCollisions() {
            const playerCar = document.getElementById('playerCar');
            const playerRect = playerCar.getBoundingClientRect();
            
            gameState.enemyCars.forEach(car => {
                const carRect = car.element.getBoundingClientRect();
                
                if (isColliding(playerRect, carRect)) {
                    gameOver();
                }
            });
        }

        function isColliding(rect1, rect2) {
            return !(rect1.right < rect2.left || 
                     rect1.left > rect2.right || 
                     rect1.bottom < rect2.top || 
                     rect1.top > rect2.bottom);
        }

        function gameOver() {
            gameState.isRunning = false;
            
            document.getElementById('finalScore').textContent = gameState.score;
            document.getElementById('finalDistance').textContent = gameState.distance;
            document.getElementById('gameOver').style.display = 'block';
            
            // Clear enemy cars
            gameState.enemyCars.forEach(car => car.element.remove());
            gameState.enemyCars = [];
        }

        function restartGame() {
            document.getElementById('gameOver').style.display = 'none';
            document.getElementById('gameArea').style.display = 'none';
            document.getElementById('startScreen').style.display = 'flex';
            
            // Reset player car position
            const playerCar = document.getElementById('playerCar');
            playerCar.style.left = '50%';
            playerCar.style.transform = 'translateX(-50%)';
        }

        function createParticles() {
            const particles = document.getElementById('particles');
            
            setInterval(() => {
                if (gameState.isRunning) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.animationDuration = (2 + Math.random() * 2) + 's';
                    particles.appendChild(particle);
                    
                    setTimeout(() => {
                        particle.remove();
                    }, 4000);
                }
            }, 200);
        }

        // PHP-like session management (simulated with localStorage)
        function saveHighScore() {
            const highScore = localStorage.getItem('neorace_highscore') || 0;
            if (gameState.score > highScore) {
                localStorage.setItem('neorace_highscore', gameState.score);
                return true;
            }
            return false;
        }

        // Add mobile touch controls
        let touchStartX = 0;
        let touchStartY = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        });

        document.addEventListener('touchend', function(e) {
            if (!gameState.isRunning) return;
            
            const touchEndX = e.changedTouches[0].clientX;
            const touchEndY = e.changedTouches[0].clientY;
            
            const deltaX = touchEndX - touchStartX;
            const deltaY = touchEndY - touchStartY;
            
            if (Math.abs(deltaX) > Math.abs(deltaY)) {
                // Horizontal swipe
                if (deltaX > 30) {
                    movePlayer(1);
                } else if (deltaX < -30) {
                    movePlayer(-1);
                }
            } else {
                // Vertical swipe
                if (deltaY < -30) {
                    accelerate();
                } else if (deltaY > 30) {
                    brake();
                }
            }
        });
    </script>
</body>
</html>