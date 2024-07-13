<html>
  <head>
    <title>Buy cool new product</title>
  </head>
  <body>
    <!-- Use action="/create-checkout-session.php" if your server is PHP based. -->
    <form action=/payment/handle method="POST">
        @csrf
      <button type="submit">Checkout</button>
    </form>
  </body>
</html>
