<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Carrito de Compras</title>
        <link rel="stylesheet" href="tienda.css">
        <style>
            h2 {
                text-decoration: underline;
                font-size: 35px;
            }
            .cart-item {
                display: flex;
                justify-content: center;
                font-size: 17.5px;
            }
            input {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="cart-container">
            <h2>Carrito de Compras</h2>
            <ul class="cart-items">
                <li class="cart-item">
                    <div class="item-details">
                        <h3 id="item-name"></h3>
                        <!--<p class="item-description">Descripción del Producto 1</p>-->
                        <span id="item-price"></span>

                        <script>
                            const valorH3 = localStorage.getItem('titulo');

                            document.getElementById('item-name').textContent = valorH3;
                        </script>

                    </div>
                    <!--<button class="remove-button">Eliminar</button>-->
                </li>
            </ul>

            <div class="total-container">
                <span class="total-label">Precio:$</span><br>
                <span id="total-amount"></span>
                <form>
                    <label class="total-label">Confirme su Usuario</label>
                    <input type="text" class="total-label" id="user" 
                        style="padding: 7.5px 15px 7.5px 15px;">
                </form>

                <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
                <script>
                    const valorSpan = localStorage.getItem('precio');
                    document.getElementById('total-amount').textContent = valorSpan;

                    function sendData() {
                        const precio = localStorage.getItem('precio');
                        const titulo = localStorage.getItem('titulo');
                        const user = document.getElementById('user').value;

                        const dtTienda = {
                            vP: precio, 
                            vT: titulo,
                            vU: user
                        };

                        axios.post('http://localhost/biblioteca/serverLibro.php', dtTienda)
                        .then(response => {
                            console.log('Success:', response.data);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });

                        /*alert(user);*/

                        /*fetch("http://localhost/biblioteca/serverLibro.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                        },
                            body: JSON.stringify(dtTienda)
                        })
                        .then(response => response.text())
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Success:', data);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });*/

                    }

                </script>
                
            </div>
            <button type="button" class="checkout-button" onclick="sendData()">Alquilar</button>
            
        </div>
    </body>
</html>