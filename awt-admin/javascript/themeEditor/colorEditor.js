function colorEditor(element_id, variable_name, inline_style) {
    var value = $(element_id).val();
    var cssText = $(inline_style).text();
  
    var rootSelectorIndex = cssText.indexOf(':root');
    var rootSelectorEndIndex = cssText.indexOf('}', rootSelectorIndex);
    var rootSelectorDeclarations = cssText.substring(rootSelectorIndex, rootSelectorEndIndex);
    rootSelectorDeclarations = rootSelectorDeclarations.split(":root {");
    var declarationLines = rootSelectorDeclarations[1].split(';');
  
    for (var i = 0; i < declarationLines.length; i++) {
      var line = declarationLines[i].trim();
  
      if (line.startsWith('--' + variable_name + ':')) {
        declarationLines[i] = "  --" + variable_name + ": " + value + ";";
      }
    }
  
    var updatedDeclarations = declarationLines.join(';\n');
    cssText = cssText.substring(0, rootSelectorIndex) + ':root {\n' + updatedDeclarations + ';\n}' + cssText.substring(rootSelectorEndIndex + 1);
    $(inline_style).text(cssText);
  }
  