# convertToRGBA
`convertToRGBA()` will convert:

 - any CSS `ColorName`
 - any 3 or 6 character HEX
 - any 4 or 8 character HEX
 
 to `rgba()` format.
 
 In every conversion, the user may set the alpha-channel transparency to a new value, if they wish to.

______

There are four functions:

 - `confirmInputType(input)`
 - `hexToRGBA(hexString, alpha)`
 - `colorKeywordToRGBA(colorKeyword, alpha)`
 - `convertToRGBA(input, alpha = null)`

_______

## `confirmInputType()`

```js
const confirmInputType = (input) => {

  let inputTypes = { colorKeyword: true, hex: true };

  if (input.startsWith('#')) {

    inputTypes.colorKeyword = false;

    input = input.substr(1);
  }

  if (input.match(/^[a-z]+$/) === false) {

    inputTypes.colorKeyword = false;
  }

  if (input.match(/[g-z]/)) {

    inputTypes.hex = false;
  }

  if ((input.length !== 3) && (input.length !== 4) && (input.length !== 6) && (input.length !== 8)) {

    inputTypes.hex = false;
  }

  return inputTypes;
}
```

## `hexToRGBA()`

```js

const hexToRGBA = (hexString, alpha) => {

  hexString = (hexString.startsWith('#')) ? hexString.substr(1) : hexString;

  let hex8 = [];
  let hex = hexString.split('');

  if ((hex.length === 3) || (hex.length === 4)) {

    hex8[0] = hex8[1] = hex[0];
    hex8[2] = hex8[3] = hex[1];
    hex8[4] = hex8[5] = hex[2];
    hex8[6] = hex8[7] = hex[3] || 'F';
  }

  else if ((hex.length === 6) || (hex.length === 8)) {

    hex8 = hex;
    hex8[6] = hex8[6] || 'F';
    hex8[7] = hex8[7] || 'F';
  }
  
  else return false;

  let r = parseInt(hex8.slice(0, 2).join(''), 16);
  let g = parseInt(hex8.slice(2, 4).join(''), 16);
  let b = parseInt(hex8.slice(4, 6).join(''), 16);
  let a = (Math.round((parseInt(hex8.slice(6, 8).join(''), 16) / 255) * 100) / 100);

  return ((alpha !== null) && (alpha <= 1) && (alpha >= 0)) ? `rgba(${r}, ${g}, ${b}, ${alpha})` : `rgba(${r}, ${g}, ${b}, ${a})`;
}
```

## `colorKeywordToRGBA()`

```js

const colorKeywordToRGBA = (colorKeyword, alpha) => {

  let rgbaValue;

  // CREATE TEMPORARY ELEMENT
  let el = document.createElement('div');

  // APPLY COLOR TO TEMPORARY ELEMENT
  el.style.color = colorKeyword;

  // APPEND TEMPORARY ELEMENT
  document.body.appendChild(el);

  // RESOLVE COLOR AS RGBA() VALUE
  
  if (colorKeyword === 'transparent') {

    alpha = ((alpha !== null) && (alpha <= 1) && (alpha >= 0)) ? alpha : 0;

    rgbaValue = window.getComputedStyle(el).color.replace('0)', alpha + ')');
  }

  else {
  
    alpha = ((alpha !== null) && (alpha <= 1) && (alpha >= 0)) ? alpha : 1;

    rgbaValue = window.getComputedStyle(el).color.replace('rgb(', 'rgba(').replace(')', ', ' + alpha + ')');
  }
  
  // REMOVE TEMPORARY ELEMENT
  document.body.removeChild(el);

  return rgbaValue;
}
```

## `convertToRGBA()`

```js

const convertToRGBA = (input, alpha = null) => {

  let rgbaValue = false;
  input = input.toLowerCase();

  const inputTypes = confirmInputType(input);

  if ((inputTypes.colorKeyword === true) && (inputTypes.hex === false)) {
    
    rgbaValue = colorKeywordToRGBA(input, alpha);

    rgbaValue = ((rgbaValue.startsWith('rgba(0, 0, 0,')) && (['black', 'transparent'].includes(input) === false)) ? false : rgbaValue;
  }

  else if ((inputTypes.colorKeyword === false) && (inputTypes.hex === true)) {

    rgbaValue = hexToRGBA(input, alpha);
  }

  else if ((inputTypes.colorKeyword === true) && (inputTypes.hex === true)) {
    
    let rgbaValue1 = hexToRGBA(input, alpha);

    let rgbaValue2 = colorKeywordToRGBA(input, alpha);

    rgbaValue = ((rgbaValue2.startsWith('rgba(0, 0, 0,')) && (['black', 'transparent'].includes(input) === false)) ? rgbaValue1 : rgbaValue2;
  }

  return rgbaValue;
}
```

## Results:

```
// HEX WITHOUT #
console.log('FF7700:', convertToRGBA('FF7700'));                    // rgba(255, 119, 0, 1)

// HEX WITH #
console.log('#FF7700:', convertToRGBA('#FF7700'));                  // rgba(255, 119, 0, 1)

// HEX WITH ALPHA
console.log('#FF0000, 0.5:', convertToRGBA('#FF0000', 0.5));        // rgba(255, 0, 0, 0.5)

// 8-CHAR HEX
console.log('#FF770088:', convertToRGBA('#FF770088'));              // rgba(255, 119, 0, 0.53)

// 8-CHAR HEX WITH ALPHA
console.log('#FF770088, 0.2:', convertToRGBA('#FF770088', 0.2));    // rgba(255, 119, 0, 0.2)

// INVALID HEX
console.log('#FF770:', convertToRGBA('#FF770'));                    // false


// BASIC COLORS
console.log('red:', convertToRGBA('red'));                          // rgba(255, 0, 0, 1)
console.log('green:', convertToRGBA('green'));                      // rgba(0, 128, 0, 1)
console.log('yellow:', convertToRGBA('yellow'));                    // rgba(255, 255, 0, 1)
console.log('blue:', convertToRGBA('blue'));                        // rgba(0, 0, 255, 1)


// SIMPLE COLORS
console.log('fuchsia:', convertToRGBA('fuchsia'));                  // rgba(255, 0, 255, 1)
console.log('lime:', convertToRGBA('lime'));                        // rgba(0, 255, 0, 1)
console.log('maroon:', convertToRGBA('maroon'));                    // rgba(128, 0, 0, 1)
console.log('navy:', convertToRGBA('navy'));                        // rgba(0, 0, 128, 1)
console.log('olive:', convertToRGBA('olive'));                      // rgba(128, 128, 0, 1)
console.log('purple:', convertToRGBA('purple'));                    // rgba(128, 0, 128, 1)
console.log('teal:', convertToRGBA('teal'));                        // rgba(0, 128, 128, 1)
console.log('transparent:', convertToRGBA('transparent'));          // rgba(0, 0, 0, 0)
console.log('transparent 2:', convertToRGBA('transparent', 0.6));   // rgba(0, 0, 0, 0.6)

// ADVANCED COLORS
console.log('blanchedalmond:', convertToRGBA('blanchedalmond'));    // rgba(255, 235, 205, 1)
console.log('coral:', convertToRGBA('coral'));                      // rgba(255, 127, 80, 1)
console.log('darkorchid:', convertToRGBA('darkorchid'));            // rgba(153, 50, 204, 1)
console.log('firebrick:', convertToRGBA('firebrick'));              // rgba(178, 34, 34, 1)
console.log('gainsboro:', convertToRGBA('gainsboro'));              // rgba(220, 220, 220, 1)
console.log('honeydew:', convertToRGBA('honeydew'));                // rgba(240, 255, 240, 1)
console.log('papayawhip:', convertToRGBA('papayawhip'));            // rgba(255, 239, 213, 1)
console.log('seashell:', convertToRGBA('seashell'));                // rgba(255, 245, 238, 1)
console.log('thistle:', convertToRGBA('thistle'));                  // rgba(216, 191, 216, 1)
console.log('wheat:', convertToRGBA('wheat'));                      // rgba(245, 222, 179, 1)

// RANDOM LETTERS
console.log('ABCD:', convertToRGBA('abcd'));                        // rgba(170, 187, 204, 0.87)
console.log('#ABCD:', convertToRGBA('#abcd'));                      // rgba(170, 187, 204, 0.87)
console.log('ABCDE:', convertToRGBA('abcde'));                      // false
```
