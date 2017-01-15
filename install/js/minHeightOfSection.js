function minHeightOfSection()
{
    $(document).ready(function() {
        var heightOfNavigator = window.innerHeight;

        var heightOfHeader = 120;
        //var heightOfFooter = 125;
        var heightOfFooter = 0;
        //var nbLine         = 2 * 1;
        var nbLine         = 1;

        var minHeightOfSection = (heightOfNavigator - (heightOfHeader + heightOfFooter +  nbLine));

        document.getElementById('section').style.minHeight=minHeightOfSection+"px";
    });
}
