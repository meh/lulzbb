<a name="write-post"></a>
<div class="write-post">
    <form>
        <textarea id="write-content"></textarea>
        <div style="text-align: right">
            <input value="Submit" type="button" 
                onclick=
                    "POST(
                        'middle',
                        'input/?forum&post&send',
                        'topic_id=<%POST-TOPIC-ID%>&'
                        + 'title=<%POST-POST-TITLE%>&'
                        + 'content='+urlencode('write-content')+'&'
                        + 'magic=<%MAGIC%>'
                     );"/>
        </div>
    </form>
</div>

