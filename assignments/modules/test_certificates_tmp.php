<?php if(!isset($RUN)) { exit(); } ?>
<form name="form1" method="post" action="index.php?module=download_test_certificate">
<table align="center">
        <tr id="drpTr3">
            <td class="text_desc">
                <label class="desc_text"><?php echo CERTIFICATE ?> :</label>
            </td>
            <td>
                 <select style="width:170px" onchange="drpIsRandom_onchange()"  id="drpCert" name="drpCert">
                     <?php echo $certificate_options ?>
                </select>
            </td>
        </tr></tr>
            <td colspan="2" align="center">
                <input type="hidden" name="ajax" value="yes" />
                <input class="btn" type="submit" id="btnSubmit" name="btnSubmit" value="<?php echo DOWNLOAD_CERTIFICATE ?>" />
            </td>
        </tr>
</table>
    </form>