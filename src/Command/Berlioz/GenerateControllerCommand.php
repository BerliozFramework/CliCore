<?php


namespace Sireniti\App\Command;


use Berlioz\Package\Atlas\EntityManager;
use Berlioz\Package\Atlas\Exception\RepositoryException;
use GetOpt\CommandInterface;
use GetOpt\GetOpt;
use PhpParser\JsonDecoder;


class GenerateControllerCommand extends AbstractCommand
{
        const CONTROLLER_PATH = 'src/Controller/';
    /**
     * OrderStatusCommand constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->setEntityManager($em);
    }


    /**
     * @param GetOpt $getOpt
     *
     * @return int
     */
    public function run(GetOpt $getOpt): int
    {
        print_r("Berlioz Framework - Generate controller" . PHP_EOL);
        $controllerName = $this->input('Please enter the name of your controller : ');

        print_r("The controller : " . $controllerName."Controller.php is created". PHP_EOL);
        $controllerFile = $controllerName."Controller.php";

        $withView = $this->input('Do you want this controller, with his view ? y or n: '. PHP_EOL);

        $fh = fopen(self::CONTROLLER_PATH.$controllerFile, 'w') or die("can't open file");
        if($withView == 'y'){
            print_r ("View created with success :". PHP_EOL);
            fwrite($fh, $this->fileGenerator($controllerName, true));
        }else{
            fwrite($fh, $this->fileGenerator($controllerName));
        }

        print_r(PHP_EOL);
        fclose($fh);
        print_r ("Thank you !". PHP_EOL);

        return 0;
    }

    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * @param $controllerName
     *
     * @return string
     */
    public function fileGenerator($controllerName, bool $withView = false): string
    {
        $file_content = '<?php' . PHP_EOL;
        $file_content .= "/**" . PHP_EOL;
        $file_content .= "* This file is part of Berlioz framework." . PHP_EOL;
        $file_content .= "*" . PHP_EOL;
        $file_content .= "* @license   https://opensource.org/licenses/MIT MIT License" . PHP_EOL;
        $file_content .= "* @copyright 2020 Ronan GIRON" . PHP_EOL;
        $file_content .= "* @author    Ronan GIRON <https://github.com/ElGigi>" . PHP_EOL;
        $file_content .= "*" . PHP_EOL;
        $file_content .= "* For the full copyright and license information, please view the LICENSE" . PHP_EOL;
        $file_content .= "* file that was distributed with this source code, to the root." . PHP_EOL;
        $file_content .= "*/" . PHP_EOL;
        $file_content .= PHP_EOL;
        $file_content .= 'declare(strict_types=1);' . PHP_EOL;
        $file_content .= PHP_EOL;
        $file_content .= 'namespace App\Controller;' . PHP_EOL;
        $file_content .= 'use Berlioz\HttpCore\Controller\AbstractController;' . PHP_EOL;
        if($withView) {
            $file_content .= 'use Psr\Http\Message\ResponseInterface;' . PHP_EOL;
            $file_content .= 'use Twig\Error\Error;' . PHP_EOL;
        }
        $file_content .= PHP_EOL;
        $file_content .= 'class ' .$controllerName.'Controller extends AbstractController'. PHP_EOL;
        $file_content .= '{' . PHP_EOL;
        $file_content .= PHP_EOL;
        if($withView){
            $file_content .= "/**" . PHP_EOL;
            $file_content .= "* Home route." . PHP_EOL;
            $file_content .= "*" . PHP_EOL;
            $file_content .= "* @return ResponseInterface|string" . PHP_EOL;
            $file_content .= "* @throws BerliozException" . PHP_EOL;
            $file_content .= "* @throws Error". PHP_EOL;
            $file_content .= '* @route("/")'. PHP_EOL;
            $file_content .= "*/". PHP_EOL;
            $file_content .= "public function home()". PHP_EOL;
            $file_content .= "{". PHP_EOL;
            $file_content .= "return $this->render('home.html.twig');". PHP_EOL;
            $file_content .= "}". PHP_EOL;
        }
        $file_content .= PHP_EOL;
        $file_content .= '}' . PHP_EOL;

        return $file_content;
    }

    /**
     * @param string|null $prompt
     *
     * @return string
     */
    public function input(string $prompt = null): string
    {
        echo $prompt;
        $handle = fopen ("php://stdin","r");
        $output = fgets ($handle);
        return trim ($output);
    }
}