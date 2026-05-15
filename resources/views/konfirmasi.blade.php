<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesanan</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #f3f4f6;
  margin: 0;
}

/* HEADER */
.header {
  display: flex;
  align-items: center;
  padding: 15px;
  background: white;
  border-bottom: 1px solid #eee;
}

.header span {
  font-size: 20px;
  margin-right: 10px;
  cursor: pointer;
}

.header h2 {
  margin: auto;
  font-size: 16px;
}

/* CONTAINER */
.container {
  max-width: 420px;
  margin: auto;
  padding: 15px;
  padding-bottom: 120px;
}

/* TITLE */
.title {
  font-weight: 600;
  margin: 15px 0 10px;
}

/* CARD */
.card {
  background: white;
  border-radius: 14px;
  padding: 12px;
  margin-bottom: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  transition: 0.2s;
}

.card:active {
  transform: scale(0.98);
}

/* MENU TERKAIT */
.related {
  display: flex;
  align-items: center;
  gap: 10px;
}

.related img {
  width: 60px;
  height: 60px;
  border-radius: 10px;
  object-fit: cover;
}

.add-btn {
  margin-left: auto;
  border: 1px solid #f97316;
  color: #f97316;
  padding: 6px 10px;
  border-radius: 10px;
  font-size: 14px;
  cursor: pointer;
  transition: 0.2s;
}

.add-btn:active {
  transform: scale(0.9);
  background: #f97316;
  color: white;
}

/* ITEM */
.item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.item h3 {
  margin: 0;
  font-size: 15px;
}

.small {
  color: #888;
  font-size: 13px;
}

/* QTY */
.qty {
  display: flex;
  align-items: center;
  gap: 10px;
}

.qty button {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  border: 1px solid #ddd;
  background: white;
  font-size: 16px;
  cursor: pointer;
  transition: 0.2s;
}

.qty button:active {
  transform: scale(0.9);
  background: #f97316;
  color: white;
}

.qty span {
  font-weight: 600;
}

/* PAYMENT */
.payment {
  background: white;
  border-radius: 15px;
  padding: 15px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.row {
  display: flex;
  justify-content: space-between;
  margin: 8px 0;
  font-size: 14px;
}

.total {
  color: #f97316;
  font-weight: 600;
}

/* FOOTER */
.footer {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  max-width: 420px;
  margin: auto;
  background: white;
  padding: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top: 1px solid #eee;
  box-shadow: 0 -4px 10px rgba(0,0,0,0.05);
}

.footer strong {
  font-size: 18px;
}

.btn {
  background: linear-gradient(135deg, #f97316, #fb923c);
  color: white;
  padding: 12px 18px;
  border-radius: 10px;
  border: none;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s;
}

.btn:active {
  transform: scale(0.95);
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
  <span onclick="history.back()">←</span>
  <h2>Pesanan</h2>
</div>

<div class="container">

  <!-- MENU TERKAIT -->
  <div class="title">Menu Terkait</div>

  <div class="card related">
    <img src="https://images.unsplash.com/photo-1551024601-bec78aea704b" />
    <div>
      <strong>Lechy Tea</strong>
      <div class="small">Rp8.181</div>
    </div>
    <div class="add-btn">＋</div>
  </div>

  <!-- ITEM -->
  <div class="title">Item yang dipesan (1)</div>

  <div class="card item">
    <div>
      <h3>Nankin Sui Mian</h3>
      <div class="small">2x No Spicy</div>
      <div class="small">Belum menambah catatan</div>
      <strong>Rp90.908</strong>
    </div>

    <div class="qty">
      <button onclick="minus()">−</button>
      <span id="qty">2</span>
      <button onclick="plus()">＋</button>
    </div>
  </div>

  <!-- PAYMENT -->
  <div class="payment">
    <div class="title">Rincian Pembayaran</div>

    <div class="row">
      <span>Subtotal</span>
      <span id="subtotal">Rp90.908</span>
    </div>

    <div class="row">
      <span>Biaya Tambahan</span>
      <span>Rp1.000</span>
    </div>

    <div class="row">
      <span>Biaya lainnya</span>
      <span id="fee">Rp9.090</span>
    </div>

    <div class="row total">
      <span>Total</span>
      <span id="total">Rp101.000</span>
    </div>
  </div>

</div>

<!-- FOOTER -->
<div class="footer">
  <div>
    <small>Total</small><br>
    <strong id="footerTotal">Rp101.000</strong>
  </div>

  <button class="btn">Lanjut Pembayaran</button>
</div>

<script>
let qty = 2;
let harga = 45454;

function rupiah(n){
  return "Rp" + Math.round(n).toLocaleString("id-ID");
}

function update(){
  let subtotal = qty * harga;
  let fee = subtotal * 0.1;
  let total = subtotal + fee + 1000;

  document.getElementById("qty").innerText = qty;
  document.getElementById("subtotal").innerText = rupiah(subtotal);
  document.getElementById("fee").innerText = rupiah(fee);
  document.getElementById("total").innerText = rupiah(total);
  document.getElementById("footerTotal").innerText = rupiah(total);
}

function plus(){
  qty++;
  update();
}

function minus(){
  if(qty>1){
    qty--;
    update();
  }
}

update();
</script>

</body>
</html>