<a name="write-topic"></a>
<div class="write-topic">
    <form>
        <table>
        <tr>
            <td><span class="parameter">Title</span></td>
            <td><input type="text" id="write-title"/></td>
        </tr>
        <tr>
            <td><span class="parameter">Subtitle</span></td>
            <td><input type="text" id="write-subtitle"/></td>
        </tr>
        </table>

        <textarea id="write-content"></textarea>
        <div style="text-align: right">
            <input value="Submit" type="button" 
                onclick=
                    "POST(
                        'middle',
                        'input/?topic&send',
                        'type=1&'
                        + 'parent=<%POST-PARENT%>&'
                        + 'title='+urlencode('write-title')+'&'
                        + 'subtitle='+urlencode('write-subtitle')+'&'
                        + 'content='+urlencode('write-content')+'&'
                        + 'magic=<%MAGIC%>'
                     );"/>
        </div>
    </form>
</div>

