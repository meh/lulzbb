<a name="write-post"></a>
<div class="write-post">
    <form>
        <textarea id="write-content"></textarea>
        <div style="text-align: right">
            <input value="Submit" type="button" 
                onclick="sendPost('<%MAGIC%>', 'middle', <%POST-TOPIC-ID%>, '<%POST-POST-TITLE%>', getContent('write-content'));"/>
        </div>
    </form>
</div>