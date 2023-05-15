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

eval("var elemPhoneFormat = $('.phoneFormat');\nfor (var a = 0; a < elemPhoneFormat.length; a++) {\n  var id = elemPhoneFormat[a].id;\n  $('#' + id).on('input', function (e) {\n    var res = e.target.value;\n    res = res.replace(/\\D+/, '').replace(/[^a-zA-Z0-9_-]/g, '').replace(/^0+/, '');\n    e.target.value = res;\n  });\n}\nvar elemPassword = $('.password');\nfor (var b = 0; b < elemPassword.length; b++) {\n  var id_p = elemPassword[b].id;\n  $('#' + id_p).on('input', function (e) {\n    var res = e.target.value;\n    res = res.replace(' ', '').replace(/[^\\w\\s]/gi, '').replace('_', '');\n    e.target.value = res;\n  });\n}\nvar elemOnlyNumber = $('.onlyNumber');\nfor (var c = 0; c < elemOnlyNumber.length; c++) {\n  var id_n = elemOnlyNumber[c].id;\n  $('#' + id_n).on('input', function (e) {\n    var res = e.target.value;\n    res = res.replace(/\\D+/, '').replace(/[^a-zA-Z0-9_-]/g, '');\n    e.target.value = res;\n  });\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvcmVnZXguanMuanMiLCJuYW1lcyI6WyJlbGVtUGhvbmVGb3JtYXQiLCIkIiwiYSIsImxlbmd0aCIsImlkIiwib24iLCJlIiwicmVzIiwidGFyZ2V0IiwidmFsdWUiLCJyZXBsYWNlIiwiZWxlbVBhc3N3b3JkIiwiYiIsImlkX3AiLCJlbGVtT25seU51bWJlciIsImMiLCJpZF9uIl0sInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvcmVnZXguanM/ZmIxMCJdLCJzb3VyY2VzQ29udGVudCI6WyJsZXQgZWxlbVBob25lRm9ybWF0ID0gJCgnLnBob25lRm9ybWF0Jyk7XG5mb3IgKGxldCBhID0gMDsgYSA8IGVsZW1QaG9uZUZvcm1hdC5sZW5ndGg7IGErKykge1xuICAgIHZhciBpZCA9IGVsZW1QaG9uZUZvcm1hdFthXS5pZDtcbiAgICAkKCcjJytpZCkub24oJ2lucHV0JywgKGUpID0+IHtcbiAgICAgICAgbGV0IHJlcyA9IGUudGFyZ2V0LnZhbHVlO1xuICAgICAgICByZXMgPSByZXMucmVwbGFjZSgvXFxEKy8sICcnKS5yZXBsYWNlKC9bXmEtekEtWjAtOV8tXS9nLCcnKS5yZXBsYWNlKC9eMCsvLCAnJyk7XG5cbiAgICAgICAgZS50YXJnZXQudmFsdWUgPSByZXM7XG4gICAgfSk7XG59XG5cbmxldCBlbGVtUGFzc3dvcmQgPSAkKCcucGFzc3dvcmQnKTtcbmZvciAobGV0IGIgPSAwOyBiIDwgZWxlbVBhc3N3b3JkLmxlbmd0aDsgYisrKSB7XG4gICAgdmFyIGlkX3AgPSBlbGVtUGFzc3dvcmRbYl0uaWQ7XG4gICAgJCgnIycraWRfcCkub24oJ2lucHV0JywgKGUpID0+IHtcbiAgICAgICAgdmFyIHJlcyA9IGUudGFyZ2V0LnZhbHVlO1xuICAgICAgICByZXMgPSByZXMucmVwbGFjZSgnICcsICcnKS5yZXBsYWNlKC9bXlxcd1xcc10vZ2ksICcnKVxuICAgICAgICAucmVwbGFjZSgnXycsICcnKTtcbiAgICAgICAgZS50YXJnZXQudmFsdWUgPSByZXM7XG4gICAgfSk7XG59XG5cbmxldCBlbGVtT25seU51bWJlciA9ICQoJy5vbmx5TnVtYmVyJyk7XG5mb3IgKGxldCBjID0gMDsgYyA8IGVsZW1Pbmx5TnVtYmVyLmxlbmd0aDsgYysrKSB7XG4gICAgdmFyIGlkX24gPSBlbGVtT25seU51bWJlcltjXS5pZDtcbiAgICAkKCcjJytpZF9uKS5vbignaW5wdXQnLCAoZSkgPT4ge1xuICAgICAgICB2YXIgcmVzID0gZS50YXJnZXQudmFsdWU7XG4gICAgICAgIHJlcyA9IHJlcy5yZXBsYWNlKC9cXEQrLywgJycpLnJlcGxhY2UoL1teYS16QS1aMC05Xy1dL2csJycpO1xuXG4gICAgICAgIGUudGFyZ2V0LnZhbHVlID0gcmVzO1xuICAgIH0pXG59XG4iXSwibWFwcGluZ3MiOiJBQUFBLElBQUlBLGVBQWUsR0FBR0MsQ0FBQyxDQUFDLGNBQWMsQ0FBQztBQUN2QyxLQUFLLElBQUlDLENBQUMsR0FBRyxDQUFDLEVBQUVBLENBQUMsR0FBR0YsZUFBZSxDQUFDRyxNQUFNLEVBQUVELENBQUMsRUFBRSxFQUFFO0VBQzdDLElBQUlFLEVBQUUsR0FBR0osZUFBZSxDQUFDRSxDQUFDLENBQUMsQ0FBQ0UsRUFBRTtFQUM5QkgsQ0FBQyxDQUFDLEdBQUcsR0FBQ0csRUFBRSxDQUFDLENBQUNDLEVBQUUsQ0FBQyxPQUFPLEVBQUUsVUFBQ0MsQ0FBQyxFQUFLO0lBQ3pCLElBQUlDLEdBQUcsR0FBR0QsQ0FBQyxDQUFDRSxNQUFNLENBQUNDLEtBQUs7SUFDeEJGLEdBQUcsR0FBR0EsR0FBRyxDQUFDRyxPQUFPLENBQUMsS0FBSyxFQUFFLEVBQUUsQ0FBQyxDQUFDQSxPQUFPLENBQUMsaUJBQWlCLEVBQUMsRUFBRSxDQUFDLENBQUNBLE9BQU8sQ0FBQyxLQUFLLEVBQUUsRUFBRSxDQUFDO0lBRTdFSixDQUFDLENBQUNFLE1BQU0sQ0FBQ0MsS0FBSyxHQUFHRixHQUFHO0VBQ3hCLENBQUMsQ0FBQztBQUNOO0FBRUEsSUFBSUksWUFBWSxHQUFHVixDQUFDLENBQUMsV0FBVyxDQUFDO0FBQ2pDLEtBQUssSUFBSVcsQ0FBQyxHQUFHLENBQUMsRUFBRUEsQ0FBQyxHQUFHRCxZQUFZLENBQUNSLE1BQU0sRUFBRVMsQ0FBQyxFQUFFLEVBQUU7RUFDMUMsSUFBSUMsSUFBSSxHQUFHRixZQUFZLENBQUNDLENBQUMsQ0FBQyxDQUFDUixFQUFFO0VBQzdCSCxDQUFDLENBQUMsR0FBRyxHQUFDWSxJQUFJLENBQUMsQ0FBQ1IsRUFBRSxDQUFDLE9BQU8sRUFBRSxVQUFDQyxDQUFDLEVBQUs7SUFDM0IsSUFBSUMsR0FBRyxHQUFHRCxDQUFDLENBQUNFLE1BQU0sQ0FBQ0MsS0FBSztJQUN4QkYsR0FBRyxHQUFHQSxHQUFHLENBQUNHLE9BQU8sQ0FBQyxHQUFHLEVBQUUsRUFBRSxDQUFDLENBQUNBLE9BQU8sQ0FBQyxXQUFXLEVBQUUsRUFBRSxDQUFDLENBQ2xEQSxPQUFPLENBQUMsR0FBRyxFQUFFLEVBQUUsQ0FBQztJQUNqQkosQ0FBQyxDQUFDRSxNQUFNLENBQUNDLEtBQUssR0FBR0YsR0FBRztFQUN4QixDQUFDLENBQUM7QUFDTjtBQUVBLElBQUlPLGNBQWMsR0FBR2IsQ0FBQyxDQUFDLGFBQWEsQ0FBQztBQUNyQyxLQUFLLElBQUljLENBQUMsR0FBRyxDQUFDLEVBQUVBLENBQUMsR0FBR0QsY0FBYyxDQUFDWCxNQUFNLEVBQUVZLENBQUMsRUFBRSxFQUFFO0VBQzVDLElBQUlDLElBQUksR0FBR0YsY0FBYyxDQUFDQyxDQUFDLENBQUMsQ0FBQ1gsRUFBRTtFQUMvQkgsQ0FBQyxDQUFDLEdBQUcsR0FBQ2UsSUFBSSxDQUFDLENBQUNYLEVBQUUsQ0FBQyxPQUFPLEVBQUUsVUFBQ0MsQ0FBQyxFQUFLO0lBQzNCLElBQUlDLEdBQUcsR0FBR0QsQ0FBQyxDQUFDRSxNQUFNLENBQUNDLEtBQUs7SUFDeEJGLEdBQUcsR0FBR0EsR0FBRyxDQUFDRyxPQUFPLENBQUMsS0FBSyxFQUFFLEVBQUUsQ0FBQyxDQUFDQSxPQUFPLENBQUMsaUJBQWlCLEVBQUMsRUFBRSxDQUFDO0lBRTFESixDQUFDLENBQUNFLE1BQU0sQ0FBQ0MsS0FBSyxHQUFHRixHQUFHO0VBQ3hCLENBQUMsQ0FBQztBQUNOIn0=\n//# sourceURL=webpack-internal:///./resources/js/regex.js\n");

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