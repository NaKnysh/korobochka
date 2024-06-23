<?php
include 'extra/header.php';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Увійти за допомогою гаманця</title>
    <style>
        body {
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/near-api-js@0.43.0/dist/near-api-js.min.js"></script>
    <script>
        async function login() {
            const { connect, keyStores, WalletConnection } = nearApi;

            const myKeyStore = new keyStores.BrowserLocalStorageKeyStore();

            const connectionConfig = {
                networkId: "testnet",
                keyStore: myKeyStore,
                nodeUrl: "https://rpc.testnet.near.org",
                walletUrl: "https://testnet.mynearwallet.com/",
                helperUrl: "https://helper.testnet.near.org",
                explorerUrl: "https://testnet.nearblocks.io",
            };

            const nearConnection = await connect(connectionConfig);
            const walletConnection = new WalletConnection(nearConnection);
            walletConnection.requestSignIn({
                contractId: "",
                methodNames: [],
                successUrl: "http://korobochka.local/login.php",
                failureUrl: "http://korobochka.local/login.php",
            });
        }

        function getQueryParams() {
            const params = {};
            window.location.search.substring(1).split("&").forEach(pair => {
                const [key, value] = pair.split("=");
                params[decodeURIComponent(key)] = decodeURIComponent(value);
            });
            return params;
        }

        document.addEventListener("DOMContentLoaded", () => {
            const params = getQueryParams();
            if (params.account_id && params.all_keys) {
                const userName = encodeURIComponent(params.account_id);
                const userToken = encodeURIComponent(params.all_keys);
                fetch('actions/login_process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ userName, userToken })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'http://korobochka.local/';
                    } else {
                        console.error('Login failed');
                    }
                });
            }
        });
    </script>
</head>
<body>
	<?php
	include 'extra/headerlogo.php';
	?>
		<div class="login-whole">
			<h1>Увійти за допомогою гаманця</h1>
			<div class="login-style">
				<div>
					<button onclick="login()" style="width:100px;">Вхід</button>
				</div>
				</br>
				<a href=http://korobochka.local/>Продовжити як гість</a>
			</div>
		</div>
	
	<?php include 'extra/footer.php';?>
</body>
</html>