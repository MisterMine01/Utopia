import os


def compile_php_code(function_folder, parameters):
    function_file = ['<?php']
    for utopia_function in os.listdir(function_folder):
        function_file.append("function " + str(".".join(utopia_function.split(".")[0:-1])) +
                             parameters + " {")
        function_command = open(os.path.join(function_folder, utopia_function)).read().split("\n")
        for command in function_command:
            function_file.append("    " + command)
        function_file.append("}")
    function_file.append("?>")
    return "\n".join(function_file)
