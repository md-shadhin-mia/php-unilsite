const app = new PIXI.Application({ width: 400, height: 400 });
document.body.appendChild(app.view);

const boxSize = 20;
let snake = [{ x: 5 * boxSize, y: 5 * boxSize }];
let direction = { x: 1, y: 0 };
let food = { x: Math.floor(Math.random() * 20) * boxSize, y: Math.floor(Math.random() * 20) * boxSize };
let score = 0;

const drawSnake = () => {
    snake.forEach((segment, index) => {
        const color = index === 0 ? 0x00FF00 : 0x00CC00; // Head is green, body is dark green
        const snakePart = new PIXI.Graphics();
        snakePart.beginFill(color);
        snakePart.drawRect(segment.x, segment.y, boxSize, boxSize);
        snakePart.endFill();
        app.stage.addChild(snakePart);
    });
};

const drawFood = () => {
    const foodGraphics = new PIXI.Graphics();
    foodGraphics.beginFill(0xFF0000);
    foodGraphics.drawRect(food.x, food.y, boxSize, boxSize);
    foodGraphics.endFill();
    app.stage.addChild(foodGraphics);
};

const updateGame = () => {
    app.stage.removeChildren(); // Clear the stage

    // Move the snake
    const head = { x: snake[0].x + direction.x * boxSize, y: snake[0].y + direction.y * boxSize };
    
    // Check if the snake eats the food
    if (head.x === food.x && head.y === food.y) {
        score++;
        food = { x: Math.floor(Math.random() * 20) * boxSize, y: Math.floor(Math.random() * 20) * boxSize }; // New food position
    } else {
        snake.pop(); // Remove the tail
    }

    // Add new head
    snake.unshift(head);

    // Draw everything
    drawSnake();
    drawFood();

    // Check for collisions with walls or self
    if (head.x < 0 || head.y < 0 || head.x >= app.renderer.width || head.y >= app.renderer.height || collision(head, snake)) {
        alert('Game Over! Your score: ' + score);
        resetGame();
    }
};

const collision = (head, array) => {
    for (let i = 1; i < array.length; i++) {
        if (head.x === array[i].x && head.y === array[i].y) {
            return true;
        }
    }
    return false;
};

const resetGame = () => {
    snake = [{ x: 5 * boxSize, y: 5 * boxSize }];
    direction = { x: 1, y: 0 };
    food = { x: Math.floor(Math.random() * 20) * boxSize, y: Math.floor(Math.random() * 20) * boxSize };
    score = 0;
};

// Control the snake's direction
document.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowUp' && direction.y === 0) {
        direction = { x: 0, y: -1 };
    } else if (event.key === 'ArrowDown' && direction.y === 0) {
        direction = { x: 0, y: 1 };
    } else if (event.key === 'ArrowLeft' && direction.x === 0) {
        direction = { x: -1, y: 0 };
    } else if (event.key === 'ArrowRight' && direction.x === 0) {
        direction = { x: 1, y: 0 };
    }
});

// Game loop
app.ticker.add(() => {
    if (app.ticker.lastTime % 3 === 0) {
        updateGame();
    }
});
