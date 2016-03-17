
<?= $this->error ?><br />

<label>Username:</label><?= $this->username ?><br />
<label>Email:</label><?= $this->email ?><br />

 <form method="post">
     <?= $this->error ?><br />
    <label>Password</label> <input type="password" name="password" value="" /><br />
    <label>Password Again</label> <input type="password" name="password_check" value="" /><br />
    <input type="submit" name="updatepw" value="Create User" />
</form>
