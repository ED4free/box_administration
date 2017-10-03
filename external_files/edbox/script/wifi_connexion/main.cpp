#include <iostream>

static bool	removeNetwork(std::string & fileContent, const std::string &ssid)
{
  int		ssidIndex;
  int		beginIndex;
  int		endIndex;
  int		stk;

  if ((ssidIndex = fileContent.find("ssid=\"" + ssid + "\"")) != std::string::npos)
    {
      stk = -1;
      beginIndex = -1;
      while ((stk = fileContent.find("network={", stk + 1)) != std::string::npos && stk < ssidIndex)
	beginIndex = stk;
      stk = 0;
      while ((stk = fileContent.find("}\n", stk + 1)) != std::string::npos && stk < ssidIndex);
      if (stk == std::string::npos || beginIndex == -1)
	return (false);
      endIndex = stk + 2;
      fileContent = fileContent.substr(0, beginIndex) + fileContent.substr(endIndex);
      return (true);
    }
  return (false);
}

int	main(int argc, char *argv[])
{
  std::string ssid;
  std::string fileContent;
  std::string stk;

  if (argc < 2)
    return (1);
  ssid = argv[1];
  while (std::getline(std::cin, stk))
    fileContent += stk + "\n";
  while (removeNetwork(fileContent, ssid));
  std::cout << fileContent;
  return (0);
}
