<h3>Tahta</h3>
<div class="board" id="board"></div>

<style>
  .board {
    display: grid;
    grid-template-columns: repeat(8, 60px);
    grid-template-rows: repeat(8, 60px);
    border: 2px solid #333;
    z-index: 1; /* Header'ın altında kalacak */
    position: relative; /* z-index çalışması için */
  }

.square {
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.light {
  background-color: #f0d9b5;
}

.dark {
  background-color: #b58863;
}

.square img {
  width: 90%;
  height: 90%;
  object-fit: contain;
  display: block;
}


</style>

<script>
// Tahta kareleri ve taşlar
const board = document.getElementById('board');
const squares = [];
for (let row = 8; row >= 1; row--) {
  for (let col = 1; col <= 8; col++) {
    const square = document.createElement('div');
    square.classList.add('square');
    if ((row + col) % 2 === 0) {
      square.classList.add('light');
    } else {
      square.classList.add('dark');
    }
    board.appendChild(square);
    squares.push(square);
  }
}

const pieceMap = {
  'p': 'siyahpiyon',
  'r': 'siyahkale',
  'n': 'siyahat',
  'b': 'siyahfil',
  'q': 'siyahvezir',
  'k': 'siyahsah',
  'P': 'beyazpiyon',
  'R': 'beyazkale',
  'N': 'beyazat',
  'B': 'beyazfil',
  'Q': 'beyazvezir',
  'K': 'beyazsah'
};

const initialPosition = 
  'rnbqkbnr' +
  'pppppppp' +
  '........' +
  '........' +
  '........' +
  '........' +
  'PPPPPPPP' +
  'RNBQKBNR';

function clearBoard() {
  squares.forEach(sq => sq.innerHTML = '');
}

function setPosition(fen) {
  clearBoard();
  for(let i=0; i<fen.length; i++) {
    const c = fen[i];
    if(c === '.') continue;
    const pieceName = pieceMap[c];
    if(pieceName) {
      const img = document.createElement('img');
      img.src = '/WebphpProje/pieces/' + pieceName + '.png';  // <== burası önemli
      squares[i].appendChild(img);
    }
  }
}

function indexToSquare(i) {
  const file = 'abcdefgh'[i % 8];
  const rank = 8 - Math.floor(i / 8);
  return file + rank;
}

function squareToIndex(sq) {
  const file = sq.charCodeAt(0) - 'a'.charCodeAt(0);
  const rank = 8 - parseInt(sq[1]);
  return rank * 8 + file;
}

let boardState = [];
function loadInitialBoard() {
  boardState = initialPosition.split('');
  setPosition(initialPosition);
}

function doMove(move) {
  if(move.length !== 4) return;
  const fromIdx = squareToIndex(move.slice(0,2));
  const toIdx = squareToIndex(move.slice(2,4));
  boardState[toIdx] = boardState[fromIdx];
  boardState[fromIdx] = '.';
}

function applyMoves(moves) {
  loadInitialBoard();
  moves.forEach(mv => doMove(mv));
  setPosition(boardState.join(''));
}

document.addEventListener('DOMContentLoaded', loadInitialBoard);
</script>
