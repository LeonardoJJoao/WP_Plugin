
function getSavedValue  (v){
    if (!localStorage.getItem(v)) {
        return "";// defualt value. 
    }
    return localStorage.getItem(v);
}

//sends chosen color to CSS
document.documentElement.style.setProperty('--chosen-color', getSavedValue('color'))

/**
 * Link:https://developer.mozilla.org/en-US/docs/Games/Tutorials/2D_breakout_game_Phaser
 * Tutorial to learn how to use Phaser.
 * Made my own Github page to upload umages in the handleImages function.
 * Added randomvelocity and choosen color to add more to the game
 */

var game = new Phaser.Game(480, 320, Phaser.AUTO, 'game-area', { preload: preload, create: create, update: update });

var ball;

var paddle;

var bricks;
var newBrick;
var brickInfo;

var scoreText;
var score = 0;

var lives;

var livesText;
var lifeLostText;

var playing = false;

var startButton;

var chosenColor;

// Loads everything needed for the game
function preload() {

    handleRemoteImages(); //Chamar images to Git
    
    Phaser.ScaleManager.NO_SCALE;
    game.scale.scaleMode = Phaser.ScaleManager.SHOW_ALL;
    game.scale.pageAlignHorizontally = true;
    game.scale.pageAlignVertically = true;
    
    game.stage.backgroundColor = '#eee';
    
    // em vez de usar a media diretamente do wordpress e usar o github retirar tudo menos o nome do png 
    // para chamar diretamente do WP fazer upload e buscar o link da media -- exemplo http://localhost/plugintest/wp-content/uploads/2019/06/background.jpg
    game.load.image('background', 'blueBackground.jpg');

    game.load.image('paddle', 'paddle.png');
    game.load.image('brick', 'brick.png');
    
    game.load.spritesheet('ball', 'wobble.png', 20, 20);
    game.load.spritesheet('button', 'button.png', 120, 40);
}

// creates the backgounds and images 
function create() {
    chosenColor = getSavedValue('color');

    lives = getSavedValue('lives');

    game.physics.startSystem(Phaser.Physics.ARCADE);
    game.physics.arcade.checkCollision.down = false;

    game.add.image(-700, -300, 'background');

    ball = game.add.sprite(game.world.width * 0.5, game.world.height - 25, 'ball');

    var currentColor = getChosenColor();
    colorTint = '0x' + currentColor
    ball.tint = colorTint;

    ball.animations.add('wobble', [0, 1, 0, 2, 0, 1, 0, 2, 0], 24);
    ball.anchor.set(0.5);

    game.physics.enable(ball, Phaser.Physics.ARCADE);

    ball.body.collideWorldBounds = true;
    ball.body.bounce.set(1);
    ball.checkWorldBounds = true;
    ball.events.onOutOfBounds.add(ballLeaveScreen, this);

    paddle = game.add.sprite(game.world.width * 0.5, game.world.height - 5, 'paddle');
    paddle.anchor.set(0.5, 1);

    game.physics.enable(paddle, Phaser.Physics.ARCADE);

    paddle.body.immovable = true;

    initBricks();

    textStyle = { font: '18px Arial', fill: '#' + currentColor };

    scoreText = game.add.text(5, 5, 'Points: 0', textStyle);

    livesText = game.add.text(game.world.width - 5, 5, 'Lives: ' + lives, textStyle);
    livesText.anchor.set(1, 0);

    lifeLostText = game.add.text(game.world.width * 0.5, game.world.height * 0.5, 'Life lost, click to continue', textStyle);
    lifeLostText.anchor.set(0.5);
    lifeLostText.visible = false;

    startButton = game.add.button(game.world.width * 0.5, game.world.height * 0.5, 'button', startGame, this, 1, 0, 2);
    startButton.anchor.set(0.5);
}

//updates the position of the ball
function update() {
    game.physics.arcade.collide(ball, paddle, ballHitPaddle);
    game.physics.arcade.collide(ball, bricks, ballHitBrick);
    
    if (playing) paddle.x = game.input.x || game.world.width * 0.5;
}

// creates all the bricks
function initBricks() {
    brickInfo = {
        width: 50,
        height: 20,
        count: {
            row: 7,
            col: 3
        },
        offset: {
            top: 50,
            left: 60
        },
        padding: 10
    }
    bricks = game.add.group();
    for (c = 0; c < brickInfo.count.col; c++) {
        for (r = 0; r < brickInfo.count.row; r++) {
            var brickX = (r * (brickInfo.width + brickInfo.padding)) + brickInfo.offset.left;
            var brickY = (c * (brickInfo.height + brickInfo.padding)) + brickInfo.offset.top;

            newBrick = game.add.sprite(brickX, brickY, 'brick');
            game.physics.enable(newBrick, Phaser.Physics.ARCADE);
            newBrick.body.immovable = true;
            newBrick.anchor.set(0.5);
            bricks.add(newBrick);
        }
    }
}

//destroys bricks when ball hits 
function ballHitBrick(ball, brick) {
    var killTween = game.add.tween(brick.scale);

    killTween.to({ x: 0, y: 0 }, 200, Phaser.Easing.Linear.None);
    killTween.onComplete.addOnce(function () {
        brick.kill();
    }, this);
    ball.animations.play('wobble');
    killTween.start();
    score += 10;
    scoreText.setText('Points: ' + score);
    if (score === brickInfo.count.row * brickInfo.count.col * 10) {
        alert('You won the game, congratulations!');
        location.reload();
    }
}

//ball leaves trough the bottom
function ballLeaveScreen() {
    lives--;
    if (lives) {
        livesText.setText('Lives: ' + lives);
        lifeLostText.visible = true;
        ball.reset(game.world.width * 0.5, game.world.height - 25);
        paddle.reset(game.world.width * 0.5, game.world.height - 5);
        game.input.onDown.addOnce(function () {
            lifeLostText.visible = false;
            ball.body.velocity.set(getRandomNumber(), getRandomNumber());
        }, this);
    }
    else {
        alert('You lost, game over!');
        location.reload();
    }
}

function ballHitPaddle(ball, paddle) {
    ball.animations.play('wobble');
    ball.body.velocity.x = -1 * 5 * (paddle.x - ball.x);
}

function startGame() {
    startButton.destroy();
    ball.body.velocity.set(getRandomNumber(), getRandomNumber());
    playing = true;
}

function getRandomNumber() {
    num = Math.floor(Math.random() * 150) + 100;
    num *= Math.floor(Math.random() * 2) == 1 ? 1 : -1; // this will add minus sign in 50% of cases( less than 1, floors to 0, more than 1, floors to 1)
    return num;
}

/**
 * this function takes care of loading the images from the remote server
 * used to evade Google Chrome Error -- Not allowed to load local files -- 
 */
function handleRemoteImages() {
    game.load.baseURL = 'https://leonardojjoao.github.io/Images.github.io/';
    game.load.crossOrigin = 'anonymous';
}

// gets the color code for the data given by the local storage
function getChosenColor() {
    var colorCode;

    if (chosenColor == "blue") colorCode = '0095DD';
    else if (chosenColor == "red") colorCode = 'FF0000';
    else if (chosenColor == "yellow") colorCode = 'FFFF00';
    else if (chosenColor == "green") colorCode = '01DF01';

    return colorCode;
}