CREATE TABLE `mod_colors` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `rgb` varchar(25) NOT NULL,
  `info` varchar(75) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO mod_colors(rgb, info, name) VALUES ('#ffffff', 'white', '#ffffff - white');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#e1e5ec', 'default', '#e1e5ec - default');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#2f353b', 'dark', '#2f353b - dark');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#3598dc', 'blue', '#3598dc - blue');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#578ebe', 'blue-madison', '#578ebe - blue-madison');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#2C3E50', 'blue-chambray', '#2C3E50 - blue-chambray');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#22313F', 'blue-ebonyclay', '#22313F - blue-ebonyclay');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#67809F', 'blue-hoki', '#67809F - blue-hoki');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#4B77BE', 'blue-steel', '#4B77BE - blue-steel');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#4c87b9', 'blue-soft', '#4c87b9 - blue-soft');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#5e738b', 'blue-dark', '#5e738b - blue-dark');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#5C9BD1', 'blue-sharp', '#5C9BD1 - blue-sharp');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#32c5d2', 'green', '#32c5d2 - green');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#1BBC9B', 'green-meadow', '#1BBC9B - green-meadow');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#1BA39C', 'green-seagreen', '#1BA39C - green-seagreen');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#36D7B7', 'green-turquoise', '#36D7B7 - green-turquoise');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#44b6ae', 'green-haze', '#44b6ae - green-haze');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#26C281', 'green-jungle', '#26C281 - green-jungle');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#3faba4', 'green-soft', '#3faba4 - green-soft');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#4DB3A2', 'green-dark', '#4DB3A2 - green-dark');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#2ab4c0', 'green-sharp', '#2ab4c0 - green-sharp');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#E5E5E5', 'grey', '#E5E5E5 - grey');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#e9edef', 'grey-steel', '#e9edef - grey-steel');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#fafafa', 'grey-cararra', '#fafafa - grey-cararra');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#555555', 'grey-gallery', '#555555 - grey-gallery');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#95A5A6', 'grey-cascade', '#95A5A6 - grey-cascade');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#BFBFBF', 'grey-silver', '#BFBFBF - grey-silver');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#ACB5C3', 'grey-salsa', '#ACB5C3 - grey-salsa');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#bfcad1', 'grey-salt', '#bfcad1 - grey-salt');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#525e64', 'grey-mint', '#525e64 - grey-mint');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#e7505a', 'red', '#e7505a - red');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#E08283', 'red-pink', '#E08283 - red-pink');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#E26A6A', 'red-sunglo', '#E26A6A - red-sunglo');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#e35b5a', 'red-intense', '#e35b5a - red-intense');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#D91E18', 'red-thunderbird', '#D91E18 - red-thunderbird');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#EF4836', 'red-flamingo', '#EF4836 - red-flamingo');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#d05454', 'red-soft', '#d05454 - red-soft');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#f36a5a', 'red-haze', '#f36a5a - red-haze');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#e43a45', 'red-mint', '#e43a45 - red-mint');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#c49f47', 'yellow', '#c49f47 - yellow');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#E87E04', 'yellow-gold', '#E87E04 - yellow-gold');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#f2784b', 'yellow-casablanca', '#f2784b - yellow-casablanca');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#f3c200', 'yellow-crusta', '#f3c200 - yellow-crusta');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#F7CA18', 'yellow-lemon', '#F7CA18 - yellow-lemon');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#F4D03F', 'yellow-saffron', '#F4D03F - yellow-saffron');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#c8d046', 'yellow-soft', '#c8d046 - yellow-soft');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#c5bf66', 'yellow-haze', '#c5bf66 - yellow-haze');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#c5b96b', 'yellow-mint', '#c5b96b - yellow-mint');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#8E44AD', 'purple', '#8E44AD - purple');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#8775a7', 'purple-plum', '#8775a7 - purple-plum');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#BF55EC', 'purple-medium', '#BF55EC - purple-medium');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#8E44AD', 'purple-studio', '#8E44AD - purple-studio');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#9B59B6', 'purple-wisteria', '#9B59B6 - purple-wisteria');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#9A12B3', 'purple-seance', '#9A12B3 - purple-seance');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#8775a7', 'purple-intense', '#8775a7 - purple-intense');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#796799', 'purple-sharp', '#796799 - purple-sharp');
INSERT INTO mod_colors(rgb, info, name) VALUES ('#8877a9', 'purple-soft', '#8877a9 - purple-soft');