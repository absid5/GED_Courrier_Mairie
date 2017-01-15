function heightOfLicenceOverflow()
{
    $(document).ready(function() {
        var heightOfSection = $('#section').css('minHeight')
        var substringMax = heightOfSection.length - 2;
        var heightOfSection = heightOfSection.substring(0, substringMax);

        var newHeightOfLicenceOverflow = heightOfSection - 350;

        if ($('#licenceOverflow').height() > 0) {
            $('#licenceOverflow').height(newHeightOfLicenceOverflow+'px');
        }
    });
}
