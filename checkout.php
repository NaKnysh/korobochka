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
        .input-field {
            margin-bottom: 10px;
            display: block;
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

		<?php
			$totalAmount = 0;
			if (!empty($_SESSION['items_in_cart'])) {
				foreach ($_SESSION['items_in_cart'] as $item_in_cart) {
					if (!empty($items[$item_in_cart])) {
						$item = $items[$item_in_cart];
						echo "<div class='item'><span>{$item['title']}</span> - {$item['price']} USD</div>";

						// Remove dollar sign and any other non-numeric characters
						$cleanedPrice = str_replace(['$', ','], '', $item['price']);
						$itemPrice = floatval($cleanedPrice);
						
						$totalAmount += $itemPrice;
					} else {
						echo "<div class='item'>Item with ID {$item_in_cart} not found.</div>";
					}
				}
			} else {
				echo "<div class='item'>Your cart is empty.</div>";
			}
		?>

		<h2>Загальна сума: <span id="totalAmount"><?php echo $totalAmount; ?></span> USD</h2>
		<h4>Для підтвердження оплати, будь ласка, вкажіть приватний ключ вашого гаманця</h4>
		<input type="text" id="privateKey" class="input-field" placeholder="Введіть приватний ключ">
		<button class="button" onclick="checkout()">Оформити замовлення</button>
	</div>
    <?php include 'extra/footer.php';?>
    <script>
	
		async function calculateNearAmount(usdAmount) {
			// Приклад конвертації. Наприклад, 1 USD = 0.1 NEAR
			const conversionRate = 0.001; // Це значення повинно бути отримано з API для реального курсу
			return usdAmount * conversionRate;
		}
	
	
        async function createAndSignTransaction(receiver) {
			const nearAPI  = window.nearApi;
			const { connect, KeyPair, keyStores, utils } = nearAPI;
			const usdAmount = parseFloat(document.getElementById('totalAmount').innerText);
			const nearAmount = await calculateNearAmount(usdAmount);
			const amount = utils.format.parseNearAmount(nearAmount.toString());

			const sender = <?php echo json_encode($_SESSION['user_name']); ?>;
			const networkId = "testnet";
            const keyStore = new keyStores.InMemoryKeyStore();
			const privateKey = document.getElementById('privateKey').value;
			
			if (!privateKey) {
                alert('Поле приватного ключа обов\'язкове для заповнення');
                return;
            }
			
			const keyPair = KeyPair.fromString(privateKey);
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
		
		async function charityTransaction(sender, receiver) {
			const nearAPI  = window.nearApi;
			const { connect, KeyPair, keyStores, utils } = nearAPI;
			const usdAmount = parseFloat(document.getElementById('totalAmount').innerText);
			const nearAmount = await calculateNearAmount(usdAmount*0.6);
			const amount = utils.format.parseNearAmount(nearAmount.toString());

			const networkId = "testnet";
            const keyStore = new keyStores.InMemoryKeyStore();
			const privateKey = "ed25519:2TFjeHEJ13Bg4mZtdcwXJVPWnpLV48gYCiDoVcSmzYDUV5NCCXeXAvtGtcP3HyaY7cYvNfRuXs7V7z1aaKzU29Si";
			
			if (!privateKey) {
                alert('Поле приватного ключа обов\'язкове для заповнення');
                return;
            }
			
			const keyPair = KeyPair.fromString(privateKey);
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
            const receiverId = 'korobochkafin.testnet';
			const charityId = 'korobochkacharity.testnet';
            const privateKey = document.getElementById('privateKey').value;

			const response = await fetch('process_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ userName: <?php echo json_encode($_SESSION['user_name']); ?> }),
                });

                const result = await response.json();

                if (result.success) {
                } else {
                    alert('Failed to process orders');
                }

            if (!privateKey) {
                alert('Поле приватного ключа обов\'язкове для заповнення');
                return;
            }
            try {
                const signedTxBase64 = await createAndSignTransaction(receiverId);
				const TxBase64 = await charityTransaction(receiverId, charityId);
                alert('Transaction completed');
				
				fetch('clear_cart.php', {
					method: 'POST'
				})
				
				window.location.href = 'index.php';
            } catch (error) {
                console.error('Error:', error);
                alert('Transaction failed');
            }
        }
    </script>
</body>
</html>