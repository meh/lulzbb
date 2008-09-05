<div id="forum">
    <Sections>
        <Sections-Group>
            <Group-Header>
            <div class="group">
                <div class="group-title"><%GROUP-TITLE%></div>

                <table class="sections">
                <tr class="description">
                    <td class="forum">Forum</td>
                    <td class="topics">Topics</td>
                    <td class="posts">Posts</td>
                    <td class="last-post">Last Post</td>
                </tr>
            </Group-Header>
            <Section-Content>
                <tr>
                    <td class="section">
                        <div class="section-title"><a href="#" class="section-title"  onclick="showSection('middle', <%POST-SECTION-ID%>);"><%SECTION-TITLE%></a> <span class="url">[<a href="<%SECTION-URL%>">URL</a>]</span></div>
                        <span class="section-subtitle"><%SECTION-SUBTITLE%></span>
                    </td>
                    <td class="count-topics"><%SECTION-TOPICS-COUNT%></td>
                    <td class="count-posts"><%SECTION-POSTS-COUNT%></td>
                    <td class="last-post">
                        <Last-Info>
                        <div><a style="white-space: pre;" href="#" onclick="showTopic('middle', <%POST-SECTION-LAST-TOPIC-ID%>, 'last', <%POST-SECTION-LAST-POST-ID%>);"><%SECTION-LAST-TOPIC-TITLE%></a> <span class="url">[<a href="<%TOPIC-URL%>">URL</a>]</span> by <b><%SECTION-LAST-USER-NICK%></b></div><span><%SECTION-LAST-POST-TIME%></span></Last-Info>
                        <No-Info>
                        <div>No topics</div>
                        </No-Info>
                    </td>
                </tr>
            </Section-Content>
            <Group-Footer>
               </table>
            </div>
            </Group-Footer>
        </Sections-Group>
    </Sections>
    
    <Topics>
    <div class="topics">
        <div class="group">
            <div class="group-title"><%SECTION-TITLE%></div>
            <%PAGER%>
        </div>

        <table class="topics">
            <tr class="header">
                <td class="topic">Topic</td>
                <td class="author">Author</td>
                <td class="posts">Posts</td>
                <td class="views">Views</td>
                <td class="last-post">Last Post</td>
            </tr>
            <Topic>
            <tr class="normal" id="topic<%TOPIC-ID%>">
                <td class="topic">
                    <div class="topic-title">
                        <a href="#" onclick="showTopic('middle', <%POST-TOPIC-ID%>);"><span class="topic-title"><%TOPIC-TITLE%></span></a> <span class="url">[<a href="<%TOPIC-URL%>">URL</a>]</span></div>
                    <span class="topic-subtitle"><%TOPIC-SUBTITLE%></span>
                </td>
                <td class="author"><%TOPIC-AUTHOR%></td>
                <td class="posts"><%TOPIC-POSTS-COUNT%></td>
                <td class="views"><%TOPIC-VIEWS-COUNT%></td>
                <td class="last-post"><div><%LAST-POST-NICK%></div><span><%LAST-POST-TIME%></span></td>
            </tr>
            </Topic>
            <No-Topic>
            <tr class="normal">
                <td colspan="5" class="no-topics">
                    No topics in this section.
                </td>
            </tr>
            </No-Topic>
        </table>
    </div>
    </Topics>
    
    <New-Topic><form class="buttons"><input type="button" value="New Topic" onclick="showTopicForm('middle', <%POST-SECTION-ID%>);"/></form></New-Topic>
</div>