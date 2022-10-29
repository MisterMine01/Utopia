import csv
import json
def load_csv(csv_path):
    csv_card = list(csv.reader(open(csv_path)))
    csv_decoded = {}
    csv_indexed = {}
    for value in csv_card[0]:
        csv_indexed[value] = csv_card[0].index(value)
    for i in range(len(csv_card)):
        if i != 0:
            csv_decoded[csv_card[i][csv_indexed["id"]]] = {
                "att": csv_card[i][csv_indexed["att"]],
                "def": csv_card[i][csv_indexed["def"]],
                "price": csv_card[i][csv_indexed["price"]],
                "rarity": csv_card[i][csv_indexed["rarity"]],
                "func": {
                    "new": csv_card[i][csv_indexed["func_new"]],
                    "died": csv_card[i][csv_indexed["func_died"]],
                    "startTurn": csv_card[i][csv_indexed["func_startturn"]],
                    "endTurn": csv_card[i][csv_indexed["func_endturn"]],
                    "eachSend": csv_card[i][csv_indexed["func_eachsend"]]
                },
                "tags": {
                    "primary": json.loads(csv_card[i][csv_indexed["tags_primary"]]),
                    "secundary": json.loads(csv_card[i][csv_indexed["tags_secondary"]])
                }
            }
    return csv_decoded

def compile_card(all_card, all_lang):
    for card in all_card:
        all_card[card]["name"] = {}
        all_card[card]["description"] = {}
    for lang in all_lang.keys():
        for card_id, value in all_lang[lang].items():
            all_card[card_id]["name"][lang] = value["name"]
            all_card[card_id]["description"][lang] = value["description"]
    return all_card
