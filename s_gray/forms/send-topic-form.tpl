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
                onclick="sendTopic('<%MAGIC%>', 'middle', <%POST-PARENT%>, 1, getContent('write-title'), getContent('write-subtitle'), getContent('write-content'));"/>
        </div>
    </form>
</div>