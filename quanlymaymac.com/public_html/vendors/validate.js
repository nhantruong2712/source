/**
 * HTH Framework Validation Helper - JavaScript Version
 *
 * @Author          Sans_Amour
 * @Copyright       2011
 */

valid = {
	
	/* Username */
	username:function(username)
	{
		return username.match(/^([-_ a-z0-9]+)$/i);
	},
	
	
	/* Name */
	name:function(name)
	{
		return name.match(/^([ a-z]+)$/i);
	},
	
	
	/* Email Address */
	email:function(email)
	{
		return email.match(/^([_\.a-z0-9]{3,})@([-_\.a-z0-9]{3,})\.([a-z]{2,})$/i);
	},
	
	
	/* Phone Number */
	phone:function(phone,strict)
	{
		if(strict == NULL)
		{
			phone = phone.replace(/([ \(\)-]+)/,'');
		}
		
		return phone.match(/^([0-9]{10})$/);
	}
}