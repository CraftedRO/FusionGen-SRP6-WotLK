{form_open('login')}
	<div id="sidebox_login" style="text-align: center;">
		<input type="text" name="login_username" id="login_username" autocomplete="username" value="" placeholder="{lang("username", "sidebox_info")}">
		<input type="password" name="login_password" id="login_password" autocomplete="current-password" value="" placeholder="{lang("password", "sidebox_info")}">
		<input type="submit" name="login_submit" value="{lang("log_in", "sidebox_info")}">
		<br><br> <a href="{$url}register">Register an Account</a>
	</div>
</form>