{form_open('password_recovery/email', $class)}
	<table style="width:80%">
		<tr>
			<td><label for="recover_email">Email</label></td>
			<td><input type="text" id="recover_email" name="recover_email" /></td>
		</tr>
		<!-- by Iuli !-->
		<tr>
			<td>
			</td>
			<td>
				<a href="{$url}password_recovery/">Do you know your username? Go to this link</a>.
			</td>
		</tr>
		<!-- end by Iuli !-->
	</table>

	<center style="margin-bottom:10px;">
		<input type="submit" name="change_submit" value="{lang("recover", "recovery")}">
	</center>
</form>