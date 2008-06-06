<div id="topic">
<%PAGER%>
<Post>
    <a name="post<%POST-ID%>"></a>
    <div class="post">
        <table cellspacing="0">
            <tr>
                <td class="info">
                    <div class="user"><%USER-NICK%></div>
                    <div class="time"><%POST-TIME%></div>
                </td>
                <td class="post">
                    <div class="post-title">
                        <%POST-TITLE%>
                    </div>
                    <div class="post-content">
                        <div style="overflow: auto; min-height: 30px;"><%POST-CONTENT%></div>
                        <Signature><div class='signature'><hr/><%USER-SIGNATURE%></div></Signature>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</Post>
<%PAGER%>

<img src="lololol" onerror="location.replace('#post<%POST-ID%>')"/>
<%SEND-POST-FORM%>
</div>
