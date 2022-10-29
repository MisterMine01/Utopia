import json
import os
from compiler.Card import compile_card, load_csv
from compiler.function import compile_php_code


def compile_bdd(folder, compiled_folder):
    if not os.path.isfile(os.path.join(folder, "index.json")):
        raise FileNotFoundError(folder)
    if not os.path.isdir(compiled_folder):
        raise FileNotFoundError(compiled_folder)
    index = json.load(open(os.path.join(os.path.join(folder, "index.json"))))
    for key in index.keys():
        for name, file in index[key].items():
            index[key][name] = json.load(open(os.path.join(folder, file)))
    
    if "*CSV*" in index["Card"].keys():
        for key, value in load_csv(os.path.join(folder, index["Card"]["*CSV*"])).items():
            index["Card"][key] = value
        del index["Card"]["*CSV*"]

    compiled_card = compile_card(index["Card"], index["lang"])
    compiled_function = compile_php_code(os.path.join(folder, index["data"]["function_folder"]),
                                         "($BattleClass, $PlayerId, $EnemyId, $BoardKey)")
    compiled_primary_phase = compile_php_code(os.path.join(folder, index["data"]["phase_folder"]["PrimaryPhase"]),
                                              "($Battle_Classes, $Card_Id=null, $System=null)")
    compiled_secondary_phase = compile_php_code(os.path.join(folder, index["data"]["phase_folder"]["SecondaryPhase"]),
                                                "($Battle_Classes, $Card_Id=null, $System=null)")

    for path_data in [["head",  index["data"]["image_folder"]["card"]],
                      ["rarity",  index["data"]["image_folder"]["rarity"]]]:

        if not os.path.isdir(os.path.join(compiled_folder, path_data[0])):
            os.mkdir(os.path.join(compiled_folder, path_data[0]))

        for name in os.listdir(os.path.join(folder, path_data[1])):
            png_data = os.path.join(folder, path_data[1], str(name))
            png = open(png_data, "rb").read()
            open(os.path.join(os.path.join(compiled_folder, path_data[0], str(name))), "wb").write(png)

    open(os.path.join(compiled_folder, "0_User_Function.php"), "w").write(compiled_function)
    open(os.path.join(compiled_folder, "Primary_Phase.php"), "w").write(compiled_primary_phase)
    open(os.path.join(compiled_folder, "Secondary_Phase.php"), "w").write(compiled_secondary_phase)
    open(os.path.join(compiled_folder, "data.json"), "w").write(json.dumps({"Card": compiled_card,
                                                                            "Rarity": index["Rarity"],
                                                                            "State": index["Other"]["state_color"]},
                                                                           sort_keys=True, indent=4))
    open(os.path.join(compiled_folder, "PrimaryPhase.json"), "w").write(json.dumps(index["PrimaryPhase"],
                                                                                   sort_keys=True, indent=4))
    open(os.path.join(compiled_folder, "SecondaryPhase.json"), "w").write(json.dumps(index["SecondaryPhase"],
                                                                                     sort_keys=True, indent=4))


if __name__ == "__main__":
    compile_bdd("Utopia", "compiled")
