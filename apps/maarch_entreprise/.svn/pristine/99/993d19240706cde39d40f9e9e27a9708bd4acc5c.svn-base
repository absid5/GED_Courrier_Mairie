<?php
/*
*   Copyright 2008-2012 Maarch
*
*   This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief Maarch quick search tool bar definition
*
* @file
* @author  Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

//require_once('../../core/init.php');
require_once 'core/class/class_core_tools.php';
$core = new core_tools();
$core->load_lang();
header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<SearchPlugin xmlns="http://www.mozilla.org/2006/browser/search/">
    <ShortName>Maarch ' . $_SESSION['config']['applicationname'] . ' ' . _GLOBAL_SEARCH . '</ShortName>
    <Description> Maarch ' . $_SESSION['config']['applicationname'] . ' ' . _GLOBAL_SEARCH . '</Description>
    <InputEncoding>UTF-8</InputEncoding>
    <Image width="16" height="16">data:image/x-icon;base64,AAABAAEAEA8AAAEAIAAkBAAAFgAAACgAAAAQAAAAHgAAAAEAIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD///8A////AP///wD///8A////AP///wD///8A////ANrT0yrXx6OP17pr49XQyUn///8A////AP///wD///8A////AP///wD///8A////AP///wDV09Me18uvfti6bdzcvGH/5cyN/+HBbP/Xum/W////AP///wD///8A////AP///wD///8A////ANjLqUXXu3PR27pe/ePIf//q38T/7OTT/+zk0//jxnr/16QX/9nRvlL///8A////AP///wD///8A////AP///wDdtk716dy6/+zk0//s5NP/7OTT/+vhyf/r4s3/48iB/9adAP/Wt2Te////AP///wD///8A////AP///wDYnQAa5MuK/+zk0//q38P/5MmD/9+5VP/huVP/3rRD/+G/Zv/WnQD/1qEO/9nRwEz///8A////AP///wDW1NQ717hotuC/ZP/fuE7/4rlV/+nJhf/s0Zv/7NGa/+fFd//Zog7/1p0A/9adAP/Yyat3////AP///wDYpBpK27FA9OG6Vv/py4j/7NGb/+zQlv/mwm3/4LVH/+C0Rv/lwWr/686T/92yPf/fvFv/2MGI4/X19QT///8A2cF1IdytM+rpyYT/471f/92tL//Xnwb/5cFq/+zRmv/s0Zv/7NGb/+zRm//lwWz/59Sk/968YP/X0s1l////ANitO5Xdu2D92agf/9adAP/WnQD/1p0A/+rNkP/s0Zv/7NGb/+zRm//s0Zv/7NGb/9+2S//p27j/17155f///wPXoxFZ59Wm/+jYr//XoAf/1p0A/9egCP/s0Zv/7NGb/+zRm//s0Zv/7NGb/+zRm//lwGn/5tGa/+C/Z//W0shm////ANmpJpXq3sD/48d9/9adAP/aphv/7NGb/+zRm//s0Zv/7NGb/+rNj//lwGn/4LVF/9urLP/ixHT/17dfwf///wD/qgAD3bI/y+zjz//dtET/3Kwu/+rNkP/lwWr/4bVI/9+yQP/iuVX/6MZ7/+zQmf/nxHX/17ps39acA0v///8A////ANehABPgu1jt4L5h+NmjFfLiuVT/58Z7/+zQmf/s0Zv/7NGb/+zRm//s0Jj/58V2/9moJdz///8C////AP///wD///8A0pYAEf+AAALYnwRI58V3/+zRm//s0Zr/6Md9/+O6V/zesDjR2aIOftWcADb/qgAD////AP///wD///8A////AP///wD///8A////ANuoJLnesj/c2aYWjNebAEDbkgAH////AP///wD///8A////AP///wD/nwAA/g8AAPAPAADgBwAA4AcAAMAHAACAAwAAgAMAAAABAACAAQAAgAAAAMABAADgAQAA/A8AAPx/AAA=</Image>
    <Url type="text/html" method="GET" template="'. $_SESSION['config']['coreurl'] . 'apps/maarch_entreprise/index.php">
      <Param name="dir" value="indexing_searching"/>
      <Param name="page" value="search_adv_result"/>
      <Param name="welcome" value="{searchTerms}"/>
      <Param name="meta[]" value="welcome%23welcome%23welcome"/>
    </Url>
    <SearchForm>http://www.maarch.org.org</SearchForm>
</SearchPlugin>';
