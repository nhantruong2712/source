<? include(dirname(__FILE__).'/header.php') ?>
 
<table width="998" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
        <tbody>
          <tr>
            <td colspan="2">

            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <td class="leftdottedline" valign="top" width="10">
                                        </td>
                    
                    <td class="centerdottedline" valign="top" align="left" bgcolor="#ffffff" style="padding:3px;">
                    
<? $count=0;foreach($fmenu as $menu){ ?>   
<? if($menu->fmenu_parid==0){ ?>                 
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td  class="catbg" height="25" style="padding-left:10px;border-bottom:0px;border-left:0px;border-right:0px;">
          <b style="text-transform: uppercase;"><?=$menu->fmenu_name?></b>
          </td>

        </tr>
        <tr>
          <td height="10">
          <img src="bms/images/spacer.gif" height="10"/>
          </td>        
        </tr>
        <tr><td>
 
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
        
<? }else{$count++; ?>    
<? if($count%6==1){ ?>       
            <tr>
<? } ?> 
            
            <td height="80" width="16.666666666667%" align="center">
            <img onclick="window.location='<?=$menu->fmenu_act?>';" style="cursor:pointer;" src="bms/images/icon/<?=$menu->fmenu_img?>" onmouseover="obj_filter(this,80);" onmouseout="obj_filter(this,100);" vspace="5" height="48" /><br />
            <a class="cart_payment" href="<?=$menu->fmenu_act?>"><?=$menu->fmenu_name?></a></td>
            
<? if($menu->isbottom && $count<6){ for($i=1;$i<=$count-4;$i++){ ?>
            <td align="center" width="16.666666666667%" height="80"></td>
<? }} ?>            
            
<? if($count%6==0 || $menu->isbottom){$count=0; ?>             
            </tr> 
		    <tr>

		      <td height="10">
		      <img src="bms/images/spacer.gif" height="10"/>
		      </td>        
		    </tr>
<? } ?>             
<? if($menu->isbottom){$count=0; ?>            
            </table>
            
            
            </td></tr>

		</table>
<? } ?> 
<? } ?>        
<? } ?> 
          
                    </td>

                    <td width="10" class="rightdottedline">&nbsp;</td>
                  </tr>
                </tbody>
            </table>
	 
 
<? include(dirname(__FILE__).'/footer.php') ?>