/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/regex.js":
/*!*******************************!*\
  !*** ./resources/js/regex.js ***!
  \*******************************/
/***/ (() => {

eval("var elemPhoneFormat = $('.phoneFormat');\nfor (var a = 0; a < elemPhoneFormat.length; a++) {\n  var id = elemPhoneFormat[a].id;\n  $('#' + id).on('input', function (e) {\n    var res = e.target.value;\n    res = res.replace(/\\D+/, '').replace(/[^a-zA-Z0-9_-]/g, '').replace(/^0+/, '');\n    e.target.value = res;\n  });\n}\nvar elemPassword = $('.password');\nfor (var b = 0; b < elemPassword.length; b++) {\n  var id_p = elemPassword[b].id;\n  $('#' + id_p).on('input', function (e) {\n    var res = e.target.value;\n    res = res.replace(' ', '').replace(/[^\\w\\s]/gi, '').replace('_', '');\n    e.target.value = res;\n  });\n}\nvar elemOnlyNumber = $('.onlyNumber');\nfor (var c = 0; c < elemOnlyNumber.length; c++) {\n  var id_n = elemOnlyNumber[c].id;\n  $('#' + id_n).on('input', function (e) {\n    var res = e.target.value;\n    res = res.replace(/\\D+/, '').replace(/[^a-zA-Z0-9_-]/g, '');\n    e.target.value = res;\n  });\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6WyJlbGVtUGhvbmVGb3JtYXQiLCIkIiwiYSIsImxlbmd0aCIsImlkIiwib24iLCJlIiwicmVzIiwidGFyZ2V0IiwidmFsdWUiLCJyZXBsYWNlIiwiZWxlbVBhc3N3b3JkIiwiYiIsImlkX3AiLCJlbGVtT25seU51bWJlciIsImMiLCJpZF9uIl0sInNvdXJjZXMiOlsid2VicGFjazovLy8uL3Jlc291cmNlcy9qcy9yZWdleC5qcz9mYjEwIl0sInNvdXJjZXNDb250ZW50IjpbImxldCBlbGVtUGhvbmVGb3JtYXQgPSAkKCcucGhvbmVGb3JtYXQnKTtcbmZvciAobGV0IGEgPSAwOyBhIDwgZWxlbVBob25lRm9ybWF0Lmxlbmd0aDsgYSsrKSB7XG4gICAgdmFyIGlkID0gZWxlbVBob25lRm9ybWF0W2FdLmlkO1xuICAgICQoJyMnK2lkKS5vbignaW5wdXQnLCAoZSkgPT4ge1xuICAgICAgICBsZXQgcmVzID0gZS50YXJnZXQudmFsdWU7XG4gICAgICAgIHJlcyA9IHJlcy5yZXBsYWNlKC9cXEQrLywgJycpLnJlcGxhY2UoL1teYS16QS1aMC05Xy1dL2csJycpLnJlcGxhY2UoL14wKy8sICcnKTtcblxuICAgICAgICBlLnRhcmdldC52YWx1ZSA9IHJlcztcbiAgICB9KTtcbn1cblxubGV0IGVsZW1QYXNzd29yZCA9ICQoJy5wYXNzd29yZCcpO1xuZm9yIChsZXQgYiA9IDA7IGIgPCBlbGVtUGFzc3dvcmQubGVuZ3RoOyBiKyspIHtcbiAgICB2YXIgaWRfcCA9IGVsZW1QYXNzd29yZFtiXS5pZDtcbiAgICAkKCcjJytpZF9wKS5vbignaW5wdXQnLCAoZSkgPT4ge1xuICAgICAgICB2YXIgcmVzID0gZS50YXJnZXQudmFsdWU7XG4gICAgICAgIHJlcyA9IHJlcy5yZXBsYWNlKCcgJywgJycpLnJlcGxhY2UoL1teXFx3XFxzXS9naSwgJycpXG4gICAgICAgIC5yZXBsYWNlKCdfJywgJycpO1xuICAgICAgICBlLnRhcmdldC52YWx1ZSA9IHJlcztcbiAgICB9KTtcbn1cblxubGV0IGVsZW1Pbmx5TnVtYmVyID0gJCgnLm9ubHlOdW1iZXInKTtcbmZvciAobGV0IGMgPSAwOyBjIDwgZWxlbU9ubHlOdW1iZXIubGVuZ3RoOyBjKyspIHtcbiAgICB2YXIgaWRfbiA9IGVsZW1Pbmx5TnVtYmVyW2NdLmlkO1xuICAgICQoJyMnK2lkX24pLm9uKCdpbnB1dCcsIChlKSA9PiB7XG4gICAgICAgIHZhciByZXMgPSBlLnRhcmdldC52YWx1ZTtcbiAgICAgICAgcmVzID0gcmVzLnJlcGxhY2UoL1xcRCsvLCAnJykucmVwbGFjZSgvW15hLXpBLVowLTlfLV0vZywnJyk7XG5cbiAgICAgICAgZS50YXJnZXQudmFsdWUgPSByZXM7XG4gICAgfSlcbn1cbiJdLCJtYXBwaW5ncyI6IkFBQUEsSUFBSUEsZUFBZSxHQUFHQyxDQUFDLENBQUMsY0FBYyxDQUFDO0FBQ3ZDLEtBQUssSUFBSUMsQ0FBQyxHQUFHLENBQUMsRUFBRUEsQ0FBQyxHQUFHRixlQUFlLENBQUNHLE1BQU0sRUFBRUQsQ0FBQyxFQUFFLEVBQUU7RUFDN0MsSUFBSUUsRUFBRSxHQUFHSixlQUFlLENBQUNFLENBQUMsQ0FBQyxDQUFDRSxFQUFFO0VBQzlCSCxDQUFDLENBQUMsR0FBRyxHQUFDRyxFQUFFLENBQUMsQ0FBQ0MsRUFBRSxDQUFDLE9BQU8sRUFBRSxVQUFDQyxDQUFDLEVBQUs7SUFDekIsSUFBSUMsR0FBRyxHQUFHRCxDQUFDLENBQUNFLE1BQU0sQ0FBQ0MsS0FBSztJQUN4QkYsR0FBRyxHQUFHQSxHQUFHLENBQUNHLE9BQU8sQ0FBQyxLQUFLLEVBQUUsRUFBRSxDQUFDLENBQUNBLE9BQU8sQ0FBQyxpQkFBaUIsRUFBQyxFQUFFLENBQUMsQ0FBQ0EsT0FBTyxDQUFDLEtBQUssRUFBRSxFQUFFLENBQUM7SUFFN0VKLENBQUMsQ0FBQ0UsTUFBTSxDQUFDQyxLQUFLLEdBQUdGLEdBQUc7RUFDeEIsQ0FBQyxDQUFDO0FBQ047QUFFQSxJQUFJSSxZQUFZLEdBQUdWLENBQUMsQ0FBQyxXQUFXLENBQUM7QUFDakMsS0FBSyxJQUFJVyxDQUFDLEdBQUcsQ0FBQyxFQUFFQSxDQUFDLEdBQUdELFlBQVksQ0FBQ1IsTUFBTSxFQUFFUyxDQUFDLEVBQUUsRUFBRTtFQUMxQyxJQUFJQyxJQUFJLEdBQUdGLFlBQVksQ0FBQ0MsQ0FBQyxDQUFDLENBQUNSLEVBQUU7RUFDN0JILENBQUMsQ0FBQyxHQUFHLEdBQUNZLElBQUksQ0FBQyxDQUFDUixFQUFFLENBQUMsT0FBTyxFQUFFLFVBQUNDLENBQUMsRUFBSztJQUMzQixJQUFJQyxHQUFHLEdBQUdELENBQUMsQ0FBQ0UsTUFBTSxDQUFDQyxLQUFLO0lBQ3hCRixHQUFHLEdBQUdBLEdBQUcsQ0FBQ0csT0FBTyxDQUFDLEdBQUcsRUFBRSxFQUFFLENBQUMsQ0FBQ0EsT0FBTyxDQUFDLFdBQVcsRUFBRSxFQUFFLENBQUMsQ0FDbERBLE9BQU8sQ0FBQyxHQUFHLEVBQUUsRUFBRSxDQUFDO0lBQ2pCSixDQUFDLENBQUNFLE1BQU0sQ0FBQ0MsS0FBSyxHQUFHRixHQUFHO0VBQ3hCLENBQUMsQ0FBQztBQUNOO0FBRUEsSUFBSU8sY0FBYyxHQUFHYixDQUFDLENBQUMsYUFBYSxDQUFDO0FBQ3JDLEtBQUssSUFBSWMsQ0FBQyxHQUFHLENBQUMsRUFBRUEsQ0FBQyxHQUFHRCxjQUFjLENBQUNYLE1BQU0sRUFBRVksQ0FBQyxFQUFFLEVBQUU7RUFDNUMsSUFBSUMsSUFBSSxHQUFHRixjQUFjLENBQUNDLENBQUMsQ0FBQyxDQUFDWCxFQUFFO0VBQy9CSCxDQUFDLENBQUMsR0FBRyxHQUFDZSxJQUFJLENBQUMsQ0FBQ1gsRUFBRSxDQUFDLE9BQU8sRUFBRSxVQUFDQyxDQUFDLEVBQUs7SUFDM0IsSUFBSUMsR0FBRyxHQUFHRCxDQUFDLENBQUNFLE1BQU0sQ0FBQ0MsS0FBSztJQUN4QkYsR0FBRyxHQUFHQSxHQUFHLENBQUNHLE9BQU8sQ0FBQyxLQUFLLEVBQUUsRUFBRSxDQUFDLENBQUNBLE9BQU8sQ0FBQyxpQkFBaUIsRUFBQyxFQUFFLENBQUM7SUFFMURKLENBQUMsQ0FBQ0UsTUFBTSxDQUFDQyxLQUFLLEdBQUdGLEdBQUc7RUFDeEIsQ0FBQyxDQUFDO0FBQ04iLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvcmVnZXguanMuanMiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/regex.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/js/regex.js"]();
/******/ 	
/******/ })()
;