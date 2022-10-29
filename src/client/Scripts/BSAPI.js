class BattleSystemApi {
    constructor(Server, BattleId, PlayerId) {
        this.rcjs = new RcJsApi(Server);
        this.BattleId = BattleId;
        this.PlayerId = PlayerId;
    }

    Start(ClientVersion, BddVersion) {
        return this.rcjs.getJsBySystem(
                "Start",
                {
                    "BattleId": this.BattleId,
                    "PlayerId": this.PlayerId,
                    "ClientVersion": ClientVersion,
                    "BddVersion": BddVersion
                }
        );
    }
    WaitPlayer() {
        return this.rcjs.getJsBySystem(
                "WaitPlayer",
                {
                    "BattleId": this.BattleId,
                    "PlayerId": this.PlayerId
                }
        );
    }
    SendDeck(Deck) {
        return this.rcjs.getJsBySystem(
                "SendDeck",
                {
                    "BattleId": this.BattleId,
                    "PlayerId": this.PlayerId,
                    "Deck": JSON.stringify(Deck)
                }
        );
    }
    GetBattle() {
        return this.rcjs.getJsBySystem(
                "GetBattle",
                {
                    "BattleId": this.BattleId,
                    "PlayerId": this.PlayerId
                }
        );
    }
    SendBattle(System, CardId) {
        return this.rcjs.getJsBySystem(
                "SendBattle",
                {
                    "BattleId": this.BattleId,
                    "PlayerId": this.PlayerId,
                    "System": System,
                    "CardId": CardId
                }
        );
    }
}