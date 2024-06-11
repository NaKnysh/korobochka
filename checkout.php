<?php 
	session_start();
	require_once('items.php');
	  require_once 'vendor/autoload.php'; // Включаємо autoload з composer для Near API
		
	  use Dotenv\Dotenv;
	  use GuzzleHttp\Client;
	  use GuzzleHttp\Exception\RequestException;
	  
	  use \nearApiPhp\jsonRpcProvider;
	  use \nearApiPhp\keyStore;
	  use \nearApiPhp\keyPair;
	  use \nearApiPhp\transactions;
	  use \nearApiPhp\account;
	  use \nearApiPhp\connection;

	  if (!isset($_SESSION['user_name']) || empty($_SESSION['items_in_cart'])) {
		die("No user account or items in cart.");
	  }
	  
	  $dotenv = Dotenv::createImmutable(__DIR__);
	  $dotenv->load();
	  include 'extra/header.php';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформлення замовлення</title>
    <style>
        .item {
            margin-bottom: 10px;
        }
        .button {
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/near-api-js@0.43.0/dist/near-api-js.min.js"></script>
</head>
<body>
	<?php
		include 'extra/headerlogo.php';
	?>
	<div class="login-whole">
		<h1>Оформлення замовлення</h1>
		<div class="item">
			<span>сирна коробка </span> - <span>$75.00</span>
		</div>
		<div class="item">
			<span>подарункова коробка</span> - <span>$99.00</span>
		</div>
		<h2>Загальна сума: <span id="totalAmount">$174</span> NEAR</h2>
		<button class="button" onclick="checkout()">Оформити замовлення</button>
	</div>
	<?php include 'extra/footer.php';?>
    <script>
        async function createAndSignTransaction(receiver, amount) {
			const nearAPI  = window.nearApi;
			const { connect, KeyPair, keyStores, utils } = nearAPI;
			const userName = <?php echo json_encode($_SESSION['user_name']); ?> + ".testnet";
			const sender = `${userName}.testnet`;
			const networkId = "testnet";
            const keyStore = new keyStores.InMemoryKeyStore();
			const userToken = <?php echo json_encode($_SESSION['userToken']); ?>;
			const keyPair = KeyPair.fromString(userToken);
			await keyStore.setKey(networkId, sender, keyPair);
            const config = {
                networkId: 'testnet',
                keyStore,
                nodeUrl: 'https://rpc.testnet.near.org',
                walletUrl: 'https://wallet.testnet.near.org',
                helperUrl: 'https://helper.testnet.near.org',
                explorerUrl: 'https://explorer.testnet.near.org',
            };
			

			
			
            const near = await nearApi.connect(config);
            const senderAccount = await near.account(sender);
			
			const result = await senderAccount.sendMoney(receiver, amount);

        }

        async function checkout() {
            const receiverId = 'zexino.testnet';
            const amount = document.getElementById('totalAmount').innerText;

            try {
                const signedTxBase64 = await createAndSignTransaction(receiverId, amount);
                const data = await response.json();
                console.log(data);
                alert('Transaction completed');
            } catch (error) {
                console.error('Error:', error);
                alert('Transaction failed');
            }
        }
    </script>
</body>
</html>