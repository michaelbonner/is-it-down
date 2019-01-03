<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Site;

class ImportSitesFromOldWay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ro:movetonew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move the old way of storing sites to the new';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        collect(
            json_decode('["https:\/\/www.boginmunns.com\/","http:\/\/www.fertilitydr.com","https:\/\/premierequestrian.com","https:\/\/www.theleaderinme.org","https:\/\/www.miyainteriors.com\/","https:\/\/www.kilnspace.com","https:\/\/ansoncalder.com","https:\/\/www.envyscapes.com\/","https:\/\/www.ssiarts.com","http:\/\/wollamconstruction.com","https:\/\/www.sawyerglass.com\/","https:\/\/www.redolive.com\/","https:\/\/www.utility-trailer.com\/","https:\/\/www.edgehomes.com\/","https:\/\/harperprecast.com\/","http:\/\/www.highlandcustomhomes.com\/","http:\/\/www.integratedslc.com\/","http:\/\/www.reedsbuiltins.com\/","https:\/\/www.rbmn.com\/","http:\/\/www.stanfieldshutter.com\/","https:\/\/www.utility-trailer.com\/","https:\/\/www.iccu.com\/","https:\/\/littlegiantladders.com\/","https:\/\/www.challengerschool.com\/","http:\/\/www.lpadvisers.com\/","https:\/\/www.gunwerks.com\/","https:\/\/www.bluestar.com\/","https:\/\/www.verisys.com\/","http:\/\/www.youdlaw.com\/","https:\/\/www.crsengineers.com\/","https:\/\/www.impact-signs.com\/","http:\/\/beacontractor.com\/","http:\/\/www.izoncam.com\/","http:\/\/www.alderandtweed.com\/","https:\/\/www.bajadesigns.com\/","https:\/\/www.wnlaw.com\/","http:\/\/www.wasatchit.com\/","https:\/\/www.cvtravel.com\/","https:\/\/data.quiznado.com\/admin\/login","https:\/\/www.villagecleaners.com\/","https:\/\/www.dentalselect.com\/","https:\/\/trtmd.com\/","http:\/\/www.lasercentermd.com\/","https:\/\/www.cbtravel.com\/","https:\/\/parkcityjewelers.com\/","https:\/\/api.legitlocal.com\/status","https:\/\/aspireconstructionutah.com\/","https:\/\/tannerclinic.com\/","https:\/\/www.blackaspenpropertymanagement.com\/","https:\/\/legitlocal.com","https:\/\/www.cookbuilder.com\/","https:\/\/imaginewithrileyblake.com\/","https:\/\/www.andavotravel.com\/","https:\/\/www.redolive.io\/"]')
        )->each(function ($site) {
            Site::firstOrCreate(
                [
                    'url' => $site,
                ],
                [
                    'url' => $site,
                ]
            );
        });
    }
}
