function checkLicence()
{
    $(document).ready(function() {
        if ($('#checkboxLicence').attr('checked')) {
            $('#returnCheckLicence').css("display","block");
        } else {
            $('#returnCheckLicence').css("display","none");
        }
    })
}
