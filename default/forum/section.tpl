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
                        <div class="section-title"><a href="#" class="section-title"  onclick="showSection('middle', <%POST-SECTION-ID%>);"><%SECTION-TITLE%></a></div>
                        <span class="section-subtitle"><%SECTION-SUBTITLE%></span>
                    </td>
                    <td class="count-topics"><%SECTION-TOPICS-COUNT%></td>
                    <td class="count-posts"><%SECTION-POSTS-COUNT%></td>
                    <td class="last-post">
                        <Last-Info>
                        <div><a style="white-space: pre;" href="#" onclick="showTopic('middle', <%POST-SECTION-LAST-TOPIC-ID%>, <%POST-SECTION-LAST-POST-ID%>);"><%SECTION-LAST-TOPIC-TITLE%></a> by <b><%SECTION-LAST-USER-NICK%></b></div><span><%SECTION-LAST-POST-TIME%></span></Last-Info>
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

            <Pager>
            <div class="pager">
                <First>
                    <span class="first">
                    <Yes><a href="#" onclick="showSection('middle', <%SECTION-ID%>, 'first');">&laquo;</a></Yes>
                    <No>&laquo;</No>
                    </span>
                </First>
                <Previous>
                    <span class="previous">&lt;</span>
                </Previous>

                <Pages>
                <div class="pages">
                    <Page><span class="page"><a href="#" onclick="showSection('middle', <%SECTION-ID%>, <%PAGE%>);"><%PAGE%></a></span></Page>
                    <Current-Page><span class="current-page"><%PAGE%></span></Current-Page>
                </div>
                </Pages>

                <Next>
                    <span class="next">
                    <Yes></Yes>
                    <No></No>
                    </span>
                </Next>
                <Last>
                    <span class="last">
                    <Yes><a href="#" onclick="showSection('middle', <%SECTION-ID%>, 'last');">&raquo;</a></Yes>
                    <No>&raquo;</No>
                    </span>
                </Last>
            </div>
            </Pager>
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
                        <a href="#" onclick="showTopic('middle', <%POST-TOPIC-ID%>);"><span class="topic-title"><%TOPIC-TITLE%></span></a></div>
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
    
    <New-Topic><form class="buttons"><input type="button" value="New Topic" onclick="POST('middle', 'output/?forum&topic&send', 'parent=<%POST-SECTION-ID%>&id=-10');"/></form></New-Topic>
</div>
