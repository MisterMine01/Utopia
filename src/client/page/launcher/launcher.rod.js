Launcher.rod = new class {
    constructor() {}
    MainButton() {
        change_page("launcher");
    }
    BattleButton() {
        change_page("launcher/battle");
    }
    LanguageButton() {
        change_page("launcher/language");
    }
}();