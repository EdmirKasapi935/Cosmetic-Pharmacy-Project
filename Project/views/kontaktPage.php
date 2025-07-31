<?php include_once __DIR__ . '/templates/header.php'; 

  if (isset($_GET["message"])) 
    echo "<script> alert('".$_GET["message"]."') </script>";
?>
<div id="contactUs" class="contact-us-container">
    <h2> Contact Us</h2> <br>
    <form method="POST" action="/sendcontact">
        <Label>First Name:</Label> <input type="text" name="FirstName" required>
        <label style="margin-left: 5px;"> Last Name: </label> <input type="text" name="LastName" required> <br>
        <Label>Email:</Label> <input type="email" name="UserEmail" required style="margin-top: 5px; "> <br>
        <Label>Phone Number:</Label> <input type="tel" name="UserPhoneNumber" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="011-222-3333" style="margin-top: 5px;"> <br>
        <Label>Subject:</Label> <input type="text" name="UserSubject" style="margin-top: 5px; "> <br>
        <Label>Message:</Label> <textarea style="margin-top: 5px;" name="UserMessage" required></textarea> <br>
        <div class="g-recaptcha" data-sitekey="6LfmST4rAAAAALV_FY8q5sEA9bfUZ1D8wiaahPFQ"></div>
        <input style="margin-top: 5px;" type="submit" name="submission">
    </form>
</div>

<script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
</script>

<?php include_once __DIR__ . '/templates/footer.php'; ?>
