import {PopulateHelper} from "../../../../Dashboard/js/ui/Helper.js";

function CreateAccountUI() {
    const formHTML = `
        <form class="create_account" class="create_account" action="/dashboard/account_manager/create" method="post">
            <h3 class="title">Create account</h3>
            <label class="lg" for="fname">First name:</label>
            <input type="text" name="fname" id="fname" class="inp_primary lg">
            <label class="lg" for="lname">Last name:</label>
            <input type="text" name="lname" id="lname" class="inp_primary lg">
            <label class="lg" for="username">Username:</label>
            <input type="text" name="username" id="username" class="inp_primary lg">
            <label class="lg" for="email">Email address:</label>
            <input type="email" name="email" class="inp_primary lg" id="email">
            <label class="lg" for="password1">Password: </label>
            <input type="password" name="password1" id="password1" class="inp_primary lg">
            <label class="lg" for="password2">Confirm password: </label>
            <input type="password" name="password2" id="password2" class="inp_primary lg">
            <label class="lg" for="plevel">Role:</label>
            <select name="perm_level" id="plevel" class="select_primary lg">
                <option value="1">Moderator</option>
                <option value="2">Author</option>
            </select>
            <button class="btn_secondary" type="submit">
                Create <i class="fas fa-plus-square"></i>
            </button>
        </form>
    `;
    const tempContainer = document.createElement('div');
    tempContainer.innerHTML = formHTML;

    const form = tempContainer.firstElementChild;

    PopulateHelper(" ", form);
}


document.addEventListener('DOMContentLoaded', (e) => {
    document.querySelector("#add_account").addEventListener("click", CreateAccountUI);
});

