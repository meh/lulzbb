<div id="register-screen">
    <div id="error" style="width: 400px; margin: 0 auto; border: 1px solid #880000;">
        Please fill all fields correctly ;3
    </div><br/>
    <form onsubmit="return register('register');">
    <table>
        <tr>
            <td>
                <span class="parameter">Username </span>
            </td>
            <td>
                <input id="username" type="text"
                    onblur="register('username');"/>
            </td>
            <td id="username-error" class="error-message">
                Insert username from 1 to 30 chars.
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 0;">
                <span class="parameter">Email </span>
            </td>
            <td style="border-bottom: 0;">
                <input id="email" type="text"
                    onblur="register('email');"/><br/>
            </td>
            <td id="email-error" class="error-message" rowspan="2"
                style="border-left: 1px solid #880000;">
                    Insert a valid email address.
            </td>
        </tr>
        <tr>
            <td>
                <span class="parameter">Email confirmation </span>
            </td>
            <td>
                <input id="email-confirmation" type="text"
                    onblur="register('email');"/>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 0;">
                <span class="parameter">Password </span>
            </td>
            <td style="border-bottom: 0;">
                <input id="password" type="password"
                    onblur="register('password');"/>
            </td>
            <td id="password-error" class="error-message" rowspan="2"
                style="border-left: 1px solid #880000;">
                    Insert a password from 6 to 30 chars.
            </td>
        </tr>
        <tr>
            <td>
                <span class="parameter">Password confirmation </span>
            </td>
            <td>
                <input id="password-confirmation" type="password"
                    onblur="register('password');"/>
            </td>
        </tr>
    </table>
    <input value="Registration" type="submit"/>
    </form>
</div>
