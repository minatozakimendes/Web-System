const validation = new JustValidate("#signup");

validation
    .addField("#name", [
        { rule: "required" }
    ])
    .addField("#email", [
        { rule: "required" },
        { rule: "email" }
    ])
    .addField("#password", [
        { rule: "required" },
        { rule: "password" }
    ])
    .addField("#confirm-password", [
        {
            validator: (value, fields) => value === fields["#password"].elem.value,
            errorMessage: "Passwords should match"
        }
    ])
    .onSuccess(() => {
        document.getElementById("signup").submit();
    });
