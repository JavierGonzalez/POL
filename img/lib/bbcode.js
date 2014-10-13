search = new Array(
          /\[img\](.*?)=\1\[\/img\]/,
          /\[url=([\w]+?:\/\/[^ \\"\n\r\t<]*?)\](.*?)\[\/url\]/,
          /\[url\]((www|ftp|)\.[^ \\"\n\r\t<]*?)\[\/url\]/,
          /\[url=((www|ftp|)\.[^ \\"\n\r\t<]*?)\](.*?)\[\/url\]/,
          /\[email\](([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+))\[\/email\]/,
          /\[b\](.*?)\[\/b\]/,
          /\[url\](http:\/\/[^ \\"\n\r\t<]*?)\[\/url\]/);

replace = new Array(
          "<img src=\"$1\" alt=\"An image\">",
          "<a href=\"$1\" target=\"blank\">$2</a>",
          "<a href=\"http://$1\" target=\"blank\">$1</a>",
          "<a href=\"$1\" target=\"blank\">$1</a>",
          "<a href=\"mailto:$1\">$1</a>",
          "<b>$1</b>",
          "<a href=\"$1\" target=\"blank\">$1</a>");
for(i = 0; i < search.length; i++) {
     text = text.replace(search[i],replace[i]);
}